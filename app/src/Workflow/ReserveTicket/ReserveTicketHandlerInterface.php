<?php

declare(strict_types=1);

namespace App\Workflow\ReserveTicket;

use App\Command\CancelTicketCommand;
use App\Command\ReserveTicketCommand;
use Ramsey\Uuid\UuidInterface;
use Spiral\Cqrs\Attribute\CommandHandler;
use Spiral\TemporalBridge\Workflow\RunningWorkflow;

interface ReserveTicketHandlerInterface
{
    public function reserve(ReserveTicketCommand $command): UuidInterface;

    public function cancel(CancelTicketCommand $command): void;
}
