<?php

declare(strict_types=1);

namespace App\Workflow\ReserveTicket;

use App\Entity\Auditorium\ReservedSeat;
use App\Repository\ReservationRepositoryInterface;
use Cycle\ORM\EntityManagerInterface;

class BuyTicketActivity implements BuyTicketActivityInterface
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservations,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function pay(string $reservationId): array
    {
        $reservation = $this->reservations->getByPK($reservationId);
        $reservation->markAsPaid();

        $this->entityManager->persist($reservation);
        $this->entityManager->run();
        $this->entityManager->clean();

        return \array_map(
            fn(ReservedSeat $seat) => $seat->getSeat()->getId(),
            $reservation->getSeats()
        );
    }
}
