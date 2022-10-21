<?php

declare(strict_types=1);

namespace App\Services\Mappers;

use App\Entity\Auditorium\Seat;
use Spiral\Shared\Services\Cinema\v1\DTO;

final class AuditoriumFactory
{
    public static function fromScreeningEntity(\App\Entity\Auditorium $auditorium): DTO\Auditorium
    {
        return new DTO\Auditorium([
            'id' => $auditorium->getId(),
            'name' => $auditorium->getName(),
            'seats' => \array_map(
                fn(Seat $seat) => SeatFactory::fromSeatEntity($seat),
                $auditorium->getSeats()
            ),
        ]);
    }
}
