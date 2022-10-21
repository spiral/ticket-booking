<?php

declare(strict_types=1);

namespace App\Services\Cinema;

use App\Application\Command\BuyTicketCommand;
use App\Application\Command\ReserveTicketCommand;
use Spiral\Cqrs\CommandBusInterface;
use Spiral\Shared\CQRS\Command\ChargeMoneyCommand;
use Spiral\Shared\CQRS\Query\GetUserQuery;
use App\Entity\Auditorium\ReservedSeat;
use App\Entity\Reservation;
use App\Entity\Screening;
use App\Repository\Auditorium\ReservedSeatRepositoryInterface;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\ScreeningRepositoryInterface;
use App\Services\Mappers\ReservationFactory;
use App\Services\Mappers\ScreeningFactory;
use App\Services\Mappers\ScreeningFullFactory;
use App\Services\Mappers\SeatFactory;
use App\Workflow\BuyTicket\BuyTicketHandlerInterface;
use App\Workflow\ReserveTicket\ReserveTicketHandlerInterface;
use Cycle\Database\Injection\Parameter;
use Ramsey\Uuid\Uuid;
use Spiral\Cqrs\QueryBusInterface;
use Spiral\RoadRunner\GRPC;
use Spiral\Shared\Mappers\TimestampFactory;
use Spiral\Shared\Services\Cinema\v1\CinemaServiceInterface;
use Spiral\Shared\Services\Cinema\v1\DTO;
use Spiral\Shared\Services\Payment\v1\PaymentServiceInterface;

final class CinemaService implements CinemaServiceInterface
{
    public function __construct(
        private readonly ReserveTicketHandlerInterface $workflow,
        private readonly ScreeningRepositoryInterface $screenings,
        private readonly ReservationRepositoryInterface $reservations,
        private readonly BuyTicketHandlerInterface $buyTicketHandler,
        private readonly ReservedSeatRepositoryInterface $reservedSeats,
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus
    ) {
    }

    public function Reserve(
        GRPC\ContextInterface $ctx,
        DTO\ReserveRequest $in
    ): DTO\ReserveResponse {
        $response = $this->CheckSeats(
            $ctx,
            new DTO\CheckSeatsRequest([
                'screening_id' => $in->getScreeningId(),
                'seats' => $in->getSeats(),
            ])
        );

        if (!empty($response->getErrorMessage())) {
            throw new \Exception($response->getErrorMessage());
        }

        $seatIds = [];
        foreach ($in->getSeats() as $seat) {
            $seatIds[] = $seat->getId();
        }

        $workflow = $this->workflow->reserve(
            $command = new ReserveTicketCommand(
                $in->getScreeningId(),
                $in->getReservationTypeId(),
                $in->getUserId(),
                $seatIds
            )
        );

        do {
            $expiresAtTimestamp = $workflow->getExpiresAt();
        } while ($expiresAtTimestamp === 0);

        $reservation = new DTO\Reservation([
            'uuid' => $command->reservationId->toString(),
            'seats' => $in->getSeats(),
            'created_at' => TimestampFactory::now(),
            'expires_at' => TimestampFactory::fromTimestamp($expiresAtTimestamp),
        ]);


        return new DTO\ReserveResponse([
            'reservation' => $reservation,
        ]);
    }

    public function Cancel(
        GRPC\ContextInterface $ctx,
        DTO\CancelRequest $in
    ): DTO\CancelResponse {
        return new DTO\CancelResponse([
            'status' => true,
        ]);
    }

    public function Purchase(
        GRPC\ContextInterface $ctx,
        DTO\PurchaseRequest $in
    ): DTO\PurchaseResponse {
        $reservation = $this->reservations->getByPK($in->getReservationId());
        $user = $this->queryBus->ask(new GetUserQuery($reservation->getUserId()));

        $receipt = $this->commandBus->dispatch(
            new ChargeMoneyCommand(
                description: 'Purchase tickets',
                paymentMethod: 'card',
                email: $user->getEmail(),
                money: $reservation->getTotalCost()
            )
        );

        $this->buyTicketHandler->buy(
            new BuyTicketCommand(
                Uuid::fromString($in->getReservationId()),
                Uuid::fromString($receipt->getTransactionId())
            )
        );

        return new DTO\PurchaseResponse([
            'receipt' => $receipt,
        ]);
    }

    public function Schedule(GRPC\ContextInterface $ctx, DTO\ScheduleRequest $in): DTO\ScheduleResponse
    {
        return new DTO\ScheduleResponse([
            'screenings' => \array_map(function (Screening $screening) {
                return ScreeningFactory::fromScreeningEntity($screening);
            }, $this->screenings->findAllActive()),
        ]);
    }

    public function Screening(
        GRPC\ContextInterface $ctx,
        DTO\ScreeningRequest $in
    ): DTO\ScreeningResponse {
        $screening = $this->screenings->getByPK($in->getId());
        return new DTO\ScreeningResponse([
            'screening' => ScreeningFullFactory::fromScreeningEntity($screening),
        ]);
    }

    public function CheckSeats(
        GRPC\ContextInterface $ctx,
        DTO\CheckSeatsRequest $in
    ): DTO\CheckSeatsResponse {
        $screening = $this->screenings->getByPK($in->getScreeningId());

        $seatIds = \array_map(fn(DTO\Seat $seat) => $seat->getId(), \iterator_to_array($in->getSeats()->getIterator()));

        if (\count($seatIds) === 0) {
            throw new \Exception('You need to select at least one seat.');
        }

        $reservedSeats = $this->reservedSeats->findAll(
            ['screening_id' => $in->getScreeningId(), 'seat_id' => ['in' => new Parameter($seatIds)]]
        );

        $data = [
            'reserved_seats' => \array_map(
                fn(ReservedSeat $seat) => SeatFactory::fromSeatEntity($seat->getSeat()),
                $reservedSeats
            ),
        ];

        if (\count($reservedSeats) > 0) {
            $data['error_message'] = 'One of the seats is reserved.';
        }

        if ($screening->isInProgress()) {
            $data['error_message'] = 'Movie is in progress.';
        }

        return new DTO\CheckSeatsResponse($data);
    }

    public function Reservations(
        GRPC\ContextInterface $ctx,
        DTO\ReservationsRequest $in
    ): DTO\ReservationsResponse {
        $reservations = (array)$this->reservations->findByUserId($in->getUserId());

        return new DTO\ReservationsResponse([
            'reservations' => \array_map(
                fn(Reservation $reservation) => ReservationFactory::fromReservationEntity($reservation),
                $reservations
            ),
        ]);
    }
}
