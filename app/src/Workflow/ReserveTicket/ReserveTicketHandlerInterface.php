<?php

declare(strict_types=1);

namespace App\Workflow\ReserveTicket;

use App\Application\Command\CancelTicketCommand;
use App\Application\Command\ReserveTicketCommand;
use Ramsey\Uuid\UuidInterface;

interface ReserveTicketHandlerInterface
{
    public function reserve(ReserveTicketCommand $command): UuidInterface;

    public function cancel(CancelTicketCommand $command): void;
}
