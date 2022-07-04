<?php

declare(strict_types=1);

namespace App\Console;

use App\Workflow\ReserveTicket\BuyTicketActivity;
use App\Workflow\ReserveTicket\ReserveTicketActivity;
use Ramsey\Uuid\Uuid;
use Spiral\Console\Command;
use Spiral\Cqrs\CommandBusInterface;

class CancelTicketCommand extends Command
{
    protected const SIGNATURE = 'cancel:ticket {reservation}';

    public function __invoke(
        CommandBusInterface $bus,
        ReserveTicketActivity $activity
    ) {
        $command = new \App\Command\CancelTicketCommand(
            Uuid::fromString($this->argument('reservation'))
        );

        \dump($bus->dispatch($command));
        // $activity->cancel($command->reservationId->toString());
    }
}
