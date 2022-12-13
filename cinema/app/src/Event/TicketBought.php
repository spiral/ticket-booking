<?php

declare(strict_types=1);

namespace App\Event;

use App\Broadcasting\ShouldBroadcastInterface;
use App\Entity\Reservation;

final class TicketBought implements ShouldBroadcastInterface
{
    public function __construct(
        public readonly Reservation $reservation
    ) {
    }

    public function getBroadcastTopics(): iterable|string|\Stringable
    {
        return \sprintf('user#%s', $this->reservation->getUserId());
    }

    public function getPayload(): array
    {
        return [
            'screening' => [
                'id' => $this->reservation->getScreening()->getId()
            ],
            'id' => $this->reservation->getUuid(),
        ];
    }

    public function getEventName(): string
    {
        return 'cinema.tickets.bought';
    }
}
