<?php

declare(strict_types=1);

namespace App\Services\Mappers;

use Spiral\Shared\Mappers\MoneyFactory;
use Spiral\Shared\Mappers\TimestampFactory;
use Spiral\Shared\Services\Cinema\v1\DTO\Screening;

final class ScreeningFactory
{
    public static function fromScreeningEntity(\App\Entity\Screening $screening): Screening
    {
        return new Screening([
            'id' => $screening->getId(),
            'movie' => MovieFactory::fromMovieEntity($screening->getMovie()),
            'auditorium' => $screening->getAuditorium()->getName(),
            'total_seats' => $screening->getAuditorium()->getTotalSeats(),
            'starts_at' => TimestampFactory::fromDateTimeInterface($screening->getStartsAt()),
            'price' => MoneyFactory::fromMoney($screening->getPrice()),
        ]);
    }
}
