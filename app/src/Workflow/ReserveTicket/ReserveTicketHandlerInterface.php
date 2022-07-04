<?php

declare(strict_types=1);

namespace App\Workflow\ReserveTicket;

use App\Command\ReserveTicketCommand;
use Spiral\Cqrs\Attribute\CommandHandler;
use Spiral\TemporalBridge\Workflow\RunningWorkflow;

interface ReserveTicketHandlerInterface
{
    public function reserve(ReserveTicketCommand $command): RunningWorkflow;
}
