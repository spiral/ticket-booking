<?php

declare(strict_types=1);

namespace App\Workflow\ReserveTicket;

use App\Repository\Auditorium\ReservedSeatRepositoryInterface;
use App\Repository\ScreeningRepositoryInterface;
use Cycle\Database\Injection\Parameter;

class SeatsReservationChecker
{
    public function __construct(
        private readonly ScreeningRepositoryInterface $screenings,
        private readonly ReservedSeatRepositoryInterface $reservedSeats
    ) {
    }

    public function checkAvailability(int $screeningId, array $seatIds): void
    {
        $screening = $this->screenings->getByPK($screeningId);

        if ($screening->isInProgress()) {
            throw new \Exception('Movie is in progress.');
        }

        $reservedSeats = $this->reservedSeats->findAll(
            ['screening_id' => $screeningId, 'seat_id' => ['in' => new Parameter($seatIds)]]
        );

        if (\count($reservedSeats) > 0) {
            throw new \Exception('One of seats is reserved.');
        }
    }
}
