<?php

declare(strict_types=1);

namespace App\Workflow\ReserveTicket;

final class ReservationSettings
{
    public function __construct(
        public readonly string $reservationId
    ) {
    }
}
