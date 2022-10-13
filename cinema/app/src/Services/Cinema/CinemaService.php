<?php

declare(strict_types=1);

namespace App\Services\Cinema;

use App\Application\Command\BuyTicketCommand;
use App\Application\Command\ReserveTicketCommand;
use App\Entity\Auditorium\ReservedSeat;
use App\Entity\Auditorium\Seat;
use App\Entity\Reservation;
use App\Entity\Screening;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\ScreeningRepositoryInterface;
use App\Workflow\BuyTicket\BuyTicketHandlerInterface;
use App\Workflow\ReserveTicket\ReserveTicketHandlerInterface;
use App\Workflow\ReserveTicket\SeatsReservationChecker;
use Carbon\Carbon;
use Google\Protobuf\Timestamp;
use Ramsey\Uuid\Uuid;
use Spiral\RoadRunner\GRPC;
use Spiral\Shared\Services\Cinema\v1\CinemaServiceInterface;
use Spiral\Shared\Services\Cinema\v1\DTO;
use Spiral\Shared\Services\Common\v1\DTO\Money;
use Spiral\Shared\Services\Payment\v1\DTO\ChargeRequest;
use Spiral\Shared\Services\Payment\v1\DTO\Payment;
use Spiral\Shared\Services\Payment\v1\PaymentServiceInterface;
use Spiral\Telemetry\TracerInterface;

final class CinemaService implements CinemaServiceInterface
{
    public function __construct(
        private readonly TracerInterface $tracer,
        private readonly PaymentServiceInterface $paymentService,
        private readonly ReserveTicketHandlerInterface $workflow,
        private readonly SeatsReservationChecker $checker,
        private readonly ScreeningRepositoryInterface $screenings,
        private readonly ReservationRepositoryInterface $reservations,
        private readonly BuyTicketHandlerInterface $buyTicketHandler
    ) {
    }

    public function Reserve(
        GRPC\ContextInterface $ctx,
        DTO\ReserveRequest $in
    ): DTO\ReserveResponse {
        $seatIds = [];
        foreach ($in->getSeats() as $seat) {
            $seatIds[] = $seat->getId();
        }

        $this->tracer->trace(
            name: __METHOD__,
            callback: fn() => sleep(1),
            attributes: [
                'screening_id' => $in->getScreeningId(),
                'user_id' => $in->getUserId(),
                'seats' => $seatIds,
            ]
        );

        $this->checker->checkAvailability($in->getScreeningId(), $seatIds);
        $workflow = $this->workflow->reserve(
            $command = new ReserveTicketCommand(
                $in->getScreeningId(),
                $in->getReservationTypeId(),
                $in->getUserId(),
                $seatIds
            )
        );

        $createdAt = new Timestamp();
        $createdAt->fromDateTime(Carbon::now());

        do {
            $expiresAtTimestamp = $workflow->getExpiresAt();
        } while ($expiresAtTimestamp === 0);

        $expiresAt = new Timestamp();
        $expiresAt->fromDateTime(Carbon::createFromTimestamp($expiresAtTimestamp));

        $reservation = new DTO\Reservation([
            'uuid' => $command->reservationId->toString(),
            'seats' => $in->getSeats(),
            'created_at' => $createdAt,
            'expires_at' => $expiresAt,
        ]);


        return new DTO\ReserveResponse([
            'reservation' => $reservation,
        ]);
    }

    public function Cancel(
        GRPC\ContextInterface $ctx,
        DTO\CancelRequest $in
    ): DTO\CancelResponse {
        $this->tracer->trace(
            name: __METHOD__,
            callback: fn() => sleep(1),
            attributes: [
                'reservation_id' => $in->getReservationId(),
            ]
        );

        return new DTO\CancelResponse([
            'status' => true,
        ]);
    }

    public function Purchase(
        GRPC\ContextInterface $ctx,
        DTO\PurchaseRequest $in
    ): DTO\PurchaseResponse {
        $this->tracer->trace(
            name: __METHOD__,
            callback: fn() => sleep(1),
            attributes: [
                'reservation_id' => $in->getReservationId(),
            ]
        );

        $reservation = $this->reservations->getByPK($in->getReservationId());

        $response = $this->paymentService->Charge(
            $ctx,
            new ChargeRequest([
                'payment' => new Payment([
                    'description' => 'Purchase tickets',
                    'payment_method' => 'card',
                    'source' => 'Booking system',
                    'money' => new Money([
                        'amount' => $reservation->getTotalCost()->getValue(),
                        'currency' => 'USD',
                    ]),
                ]),
            ])
        );

        $this->buyTicketHandler->buy(new BuyTicketCommand(
            Uuid::fromString($in->getReservationId()),
            Uuid::fromString($response->getReceipt()->getTransactionId())
        ));

        return new DTO\PurchaseResponse([
            'receipt' => $response->getReceipt(),
        ]);
    }

    public function Schedule(
        GRPC\ContextInterface $ctx,
        DTO\ScheduleRequest $in
    ): DTO\ScheduleResponse {
        $screenings = $this->tracer->trace(
            name: __METHOD__,
            callback: fn() => \array_map(function (Screening $screening) {
                $startsAt = new Timestamp();
                $startsAt->fromDateTime(\DateTime::createFromInterface($screening->getStartsAt()));

                return new DTO\Screening([
                    'id' => $screening->getId(),
                    'movie' => new DTO\Movie([
                        'id' => $screening->getMovie()->getId(),
                        'title' => $screening->getMovie()->getTitle(),
                        'description' => $screening->getMovie()->getDescription(),
                        'duration' => $screening->getMovie()->getDuration()->minutes,
                    ]),
                    'auditorium' => $screening->getAuditorium()->getName(),
                    'total_seats' => $screening->getAuditorium()->getTotalSeats(),
                    'starts_at' => $startsAt,
                    'price' => new Money([
                        'amount' => $screening->getPrice()->value,
                        'currency' => '$',
                    ]),
                ]);
            }, $this->screenings->findAllActive())
        );

        return new DTO\ScheduleResponse([
            'screenings' => $screenings,
        ]);
    }

    public function Screening(
        GRPC\ContextInterface $ctx,
        DTO\ScreeningRequest $in
    ): DTO\ScreeningResponse {
        $screening = $this->screenings->getByPK($in->getId());

        $startsAt = new Timestamp();
        $startsAt->fromDateTime(\DateTime::createFromInterface($screening->getStartsAt()));

        return new DTO\ScreeningResponse([
            'screening' => new DTO\ScreeningFull([
                'id' => $screening->getId(),
                'movie' => new DTO\Movie([
                    'id' => $screening->getMovie()->getId(),
                    'title' => $screening->getMovie()->getTitle(),
                    'description' => $screening->getMovie()->getDescription(),
                    'duration' => $screening->getMovie()->getDuration()->minutes,
                ]),
                'auditorium' => new DTO\Auditorium([
                    'id' => $screening->getAuditorium()->getId(),
                    'name' => $screening->getAuditorium()->getName(),
                    'seats' => \array_map(fn(Seat $seat) => new DTO\Seat([
                        'id' => $seat->getId(),
                        'row' => $seat->getRow(),
                        'number' => $seat->getNumber(),
                    ]), $screening->getAuditorium()->getSeats()),
                ]),
                'starts_at' => $startsAt,
                'price' => new Money([
                    'amount' => $screening->getPrice()->value,
                    'currency' => '$',
                ]),
                'reserved_seats' => \array_map(fn(ReservedSeat $seat) => new DTO\Seat([
                    'id' => $seat->getSeat()->getId(),
                    'row' => $seat->getSeat()->getRow(),
                    'number' => $seat->getSeat()->getNumber(),
                ]), $screening->getReservedSeats()),
            ]),
        ]);
    }

    public function CheckSeats(
        GRPC\ContextInterface $ctx,
        DTO\CheckSeatsRequest $in
    ): DTO\CheckSeatsResponse {
    }

    public function Reservations(
        GRPC\ContextInterface $ctx,
        DTO\ReservationsRequest $in
    ): DTO\ReservationsResponse {
        $reservations = (array)$this->reservations->findByUserId($in->getUserId());

        return new DTO\ReservationsResponse([
            'reservations' => \array_map(
                function (Reservation $reservation) {
                    $screening = $reservation->getScreening();

                    $startsAt = new Timestamp();
                    $startsAt->fromDateTime(\DateTime::createFromInterface($screening->getStartsAt()));

                    $createdAt = new Timestamp();
                    $createdAt->fromDateTime(\DateTime::createFromInterface($reservation->getCreatedAt()));

                    $expiresAt = new Timestamp();
                    $expiresAt->fromDateTime(\DateTime::createFromInterface($reservation->getExpiresAt()));

                    $data = [
                        'uuid' => $reservation->getUuid(),
                        'seats' => \array_map(
                            fn(ReservedSeat $seat) => new DTO\Seat([
                                'id' => $seat->getSeat()->getId(),
                                'row' => $seat->getSeat()->getRow(),
                                'number' => $seat->getSeat()->getNumber(),
                            ]),
                            $reservation->getSeats()
                        ),
                        'total_cost' => new Money([
                            'amount' => $reservation->getTotalCost()->getValue(),
                            'currency' => '$',
                        ]),
                        'screening' => new DTO\Screening([
                            'id' => $screening->getId(),
                            'movie' => new DTO\Movie([
                                'id' => $screening->getMovie()->getId(),
                                'title' => $screening->getMovie()->getTitle(),
                                'description' => $screening->getMovie()->getDescription(),
                                'duration' => $screening->getMovie()->getDuration()->minutes,
                            ]),
                            'auditorium' => $screening->getAuditorium()->getName(),
                            'total_seats' => $screening->getAuditorium()->getTotalSeats(),
                            'starts_at' => $startsAt,
                            'price' => new Money([
                                'amount' => $screening->getPrice()->value,
                                'currency' => '$',
                            ]),
                        ]),
                        'created_at' => $createdAt,
                        'expires_at' => $expiresAt,
                    ];


                    if ($reservation->getCanceledAt()) {
                        $data['canceled_at'] = new Timestamp();
                        $data['canceled_at']->fromDateTime(\DateTime::createFromInterface($reservation->getExpiresAt()));
                    }

                    if ($reservation->getPaidAt()) {
                        $data['paid_at'] = new Timestamp();
                        $data['paid_at']->fromDateTime(\DateTime::createFromInterface($reservation->getPaidAt()));
                    }

                    return new DTO\Reservation($data);
                },
                $reservations
            ),
        ]);
    }
}
