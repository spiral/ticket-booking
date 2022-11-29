<?php

declare(strict_types=1);

namespace App\Event;

use Spiral\Shared\Broadcasting\ShouldBroadcastInterface;
use App\Entity\Auditorium\ReservedSeat;
use App\Entity\Reservation;

final class TicketReserved implements ShouldBroadcastInterface
{
    public function __construct(
        public readonly Reservation $reservation
    ) {
    }

    public function getBroadcastTopics(): iterable|string|\Stringable
    {
        return ['public:screening.' . $this->reservation->getScreening()->getId()];
    }

    public function getPayload(): array
    {
        return [
            'seats' => \array_map(
                fn(ReservedSeat $seat) => $seat->getSeat()->getId(),
                $this->reservation->getSeats()
            ),
        ];
    }

    public function getEventName(): string
    {
        return 'cinema.tickets.reserved';
    }
}
