<?php

declare(strict_types=1);

namespace App\Broadcast;

use App\Entity\Auditorium\ReservedSeat;
use App\Event\TicketReserved;
use App\Repository\Auditorium\ReservedSeatRepositoryInterface;
use Spiral\Broadcasting\BroadcastInterface;
use Spiral\EventBus\Attribute\Listener;

final class ReservedNotice
{
    public function __construct(
        public readonly BroadcastInterface $broadcast,
        private readonly ReservedSeatRepositoryInterface $seatRepository
    ) {
    }

    #[Listener]
    public function handle(TicketReserved $event): void
    {
        $seats = $this->seatRepository->findAll(['reservation_uuid' => $event->reservation->getUuid()]);

        $this->broadcast->publish(
            \sprintf('user.%s.reservation', $event->reservation->getUser()->getId()),
            \array_map(static fn (ReservedSeat $seat) => (string) $seat->getSeat()->getId(), $seats)
        );
    }
}
