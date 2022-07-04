<?php

declare(strict_types=1);

namespace App\Workflow\BuyTicket;

use App\Command\BuyTicketCommand;
use Spiral\Cqrs\Attribute\CommandHandler;
use Spiral\TemporalBridge\Workflow\RunningWorkflow;

interface BuyTicketHandlerInterface
{
    public function buy(BuyTicketCommand $command);
}
