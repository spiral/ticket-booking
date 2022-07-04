<?php

declare(strict_types=1);

namespace App\Workflow\BuyTicket;

use App\Repository\ReservationRepositoryInterface;
use Cycle\ORM\EntityManagerInterface;

class BuyTicketActivity implements BuyTicketActivityInterface
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservations,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function buy(string $reservationId)
    {
        $reservation = $this->reservations->getByPK($reservationId);
        $reservation->markAsPaid();

        $this->entityManager->persist($reservation);
        $this->entityManager->run();
        $this->entityManager->clean();

        return \sprintf(
            'Reservation [%s] paid at: %s',
            $reservationId,
            $reservation->getPaidAt()->format(DATE_W3C)
        );
    }
}
