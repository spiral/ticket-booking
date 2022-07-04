<?php

declare(strict_types=1);

namespace App\Console;

use App\Workflow\BuyTicket\BuyTicketActivity;
use App\Workflow\BuyTicket\BuyTicketHandlerInterface;
use Spiral\Console\Command;
use Spiral\Cqrs\CommandBusInterface;

class BuyTicketCommand extends Command
{
    protected const SIGNATURE = 'buy:ticket {reservation}';

    public function __invoke(CommandBusInterface $bus, BuyTicketActivity $activity)
    {
        $command = new \App\Command\BuyTicketCommand($this->argument('reservation'));

        $bus->dispatch($command);
        // $activity->buy($command->reservationId);
    }
}
