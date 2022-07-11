<?php

declare(strict_types=1);

namespace App\Broadcast;

use App\Event\TicketBought;
use Spiral\Broadcasting\BroadcastInterface;
use Spiral\EventBus\Attribute\Listener;

final class BoughtNotice
{
    public function __construct(
        public readonly BroadcastInterface $broadcast,
    ) {
    }

    #[Listener]
    public function handle(TicketBought $event): void
    {
        $this->broadcast->publish(
            \sprintf('user.%s.buying', $event->reservation->getUser()->getId()),
            $event->reservation->getUuid()
        );
    }
}
