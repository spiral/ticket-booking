<?php

declare(strict_types=1);

namespace App\Services\Mappers;

use App\Entity\Auditorium\ReservedSeat;
use Spiral\Shared\Mappers\MoneyFactory;
use Spiral\Shared\Mappers\TimestampFactory;
use Spiral\Shared\Services\Cinema\v1\DTO\Reservation;

final class ReservationFactory
{
    public static function fromReservationEntity(\App\Entity\Reservation $reservation): Reservation
    {
        $screening = $reservation->getScreening();

        $data = [
            'uuid' => $reservation->getUuid(),
            'seats' => \array_map(
                fn(ReservedSeat $seat) => SeatFactory::fromSeatEntity($seat->getSeat()),
                $reservation->getSeats()
            ),
            'total_cost' => MoneyFactory::fromMoney($reservation->getTotalCost()),
            'screening' => ScreeningFactory::fromScreeningEntity($screening),
            'created_at' => TimestampFactory::fromDateTimeInterface($reservation->getCreatedAt()),
            'expires_at' => TimestampFactory::fromDateTimeInterface($reservation->getExpiresAt()),
        ];

        if ($reservation->getCanceledAt()) {
            $data['canceled_at'] = TimestampFactory::fromDateTimeInterface($reservation->getCanceledAt());
        }

        if ($reservation->getPaidAt()) {
            $data['paid_at'] = TimestampFactory::fromDateTimeInterface($reservation->getPaidAt());
        }

        return new Reservation($data);
    }
}
