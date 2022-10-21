<?php

declare(strict_types=1);

namespace App\Services\Mappers;

use App\Entity\Auditorium\ReservedSeat;
use Spiral\Shared\Mappers\MoneyFactory;
use Spiral\Shared\Mappers\TimestampFactory;
use Spiral\Shared\Services\Cinema\v1\DTO\ScreeningFull;

final class ScreeningFullFactory
{
    public static function fromScreeningEntity(\App\Entity\Screening $screening): ScreeningFull
    {
        return new ScreeningFull([
            'id' => $screening->getId(),
            'movie' => MovieFactory::fromMovieEntity($screening->getMovie()),
            'auditorium' => AuditoriumFactory::fromScreeningEntity($screening->getAuditorium()),
            'starts_at' => TimestampFactory::fromDateTimeInterface($screening->getStartsAt()),
            'price' => MoneyFactory::fromMoney($screening->getPrice()),
            'reserved_seats' => \array_map(
                fn(ReservedSeat $seat) => SeatFactory::fromSeatEntity($seat->getSeat()),
                $screening->getReservedSeats()
            ),
        ]);
    }
}
