<?php

declare(strict_types=1);

namespace App\Services\Mappers;

use Spiral\Shared\Services\Cinema\v1\DTO\Seat;

final class SeatFactory
{
    public static function fromSeatEntity(\App\Entity\Auditorium\Seat $seat): Seat
    {
        return new Seat([
            'id' => $seat->getId(),
            'row' => $seat->getRow(),
            'number' => $seat->getNumber(),
        ]);
    }
}
