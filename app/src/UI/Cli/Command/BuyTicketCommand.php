<?php

declare(strict_types=1);

namespace App\UI\Cli\Command;

use App\Workflow\ReserveTicket\BuyTicketActivity;
use Ramsey\Uuid\Uuid;
use Spiral\Console\Command;
use Spiral\Cqrs\CommandBusInterface;

class BuyTicketCommand extends Command
{
    protected const SIGNATURE = 'buy:ticket {reservation}';

    public function __invoke(
        CommandBusInterface $bus,
        BuyTicketActivity $activity
    ) {
        $command = new \App\Application\Command\BuyTicketCommand(
            Uuid::fromString($this->argument('reservation'))
        );

        \dump($bus->dispatch($command));
        // $activity->pay($command->reservationId->toString());
    }
}
