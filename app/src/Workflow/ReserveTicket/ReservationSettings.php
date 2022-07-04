<?php

declare(strict_types=1);

namespace App\Workflow\ReserveTicket;

use Ramsey\Uuid\UuidInterface;

final class ReservationSettings
{
    public function __construct(
        public readonly string $reservationId,
        public readonly \DateInterval $ttl
    ) {
    }
}
