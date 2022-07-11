<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Reservation;

final class TicketBought
{
    public function __construct(
        public readonly Reservation $reservation
    ) {
    }
}
