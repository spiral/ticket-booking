<?php

declare(strict_types=1);

namespace App\Workflow\ReserveTicket;

use App\Entity\Auditorium\ReservedSeat;
use App\Entity\Reservation;
use App\Event\ReservationCanceled;
use App\Event\TicketReserved;
use App\Repository\Auditorium\SeatRepositoryInterface;
use App\Repository\Reservation\TypeRepositoryInterface;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\ScreeningRepositoryInterface;
use Cycle\Database\Injection\Parameter;
use Cycle\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Spiral\RoadRunner\Metrics\MetricsInterface;

class ReserveTicketActivity implements ReserveTicketActivityInterface
{
    public function __construct(
        private readonly ScreeningRepositoryInterface $screenings,
        private readonly TypeRepositoryInterface $reservationType,
        private readonly ReservationRepositoryInterface $reservations,
        private readonly SeatRepositoryInterface $seats,
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly MetricsInterface $metrics
    ) {
    }

    public function reserve(
        string $reservationId,
        int $screeningId,
        int $reservationTypeId,
        int $userId,
        array $seatIds
    ): int {
        $screening = $this->screenings->getByPK($screeningId);
        $reservationType = $this->reservationType->getByPK($reservationTypeId);

        // TODO query from screening auditorium
        // TODO check total found seats equal to reqested seats
        $seats = $this->seats->findAll([
            'id' => ['in' => new Parameter($seatIds)],
        ]);

        $reservation = new Reservation(
            $reservationId,
            $screening,
            $reservationType,
            $userId
        );

        foreach ($seats as $seat) {
            $reservation->reserveSeat(new ReservedSeat($seat, $reservation));
        }

        $this->entityManager->persist($reservation);
        $this->entityManager->run();
        $this->entityManager->clean();

        $this->eventDispatcher->dispatch(new TicketReserved($reservation));

        $this->metrics->add('reservations', 1);
        $this->metrics->add('tickets_reservation', \count($seats), [
            $screening->getMovie()->getTitle(),
            $screening->getAuditorium()->getName(),
            (string) $screening->getPrice()->getValue(),
        ]);

        return $reservation->getExpiresAt()->getTimestamp();
    }

    public function cancel(string $reservationId)
    {
        $reservation = $this->reservations->getByPK($reservationId);

        $this->metrics->sub('reservations', 1);

        if ($reservation->isPaid()) {
            throw new \Exception(\sprintf('reservation %s was paid', $reservationId));
        }

        $reservation->markAsCanceled();

        $this->entityManager->persist($reservation);

        foreach ($reservation->getSeats() as $seat) {
            $this->entityManager->delete($seat);
        }

        $this->entityManager->run();

        $this->eventDispatcher->dispatch(
            new ReservationCanceled($reservation)
        );

        return \sprintf(
            'Reservation [%s] canceled at: %s',
            $reservationId,
            $reservation->getCanceledAt()->format(DATE_W3C)
        );
    }
}
