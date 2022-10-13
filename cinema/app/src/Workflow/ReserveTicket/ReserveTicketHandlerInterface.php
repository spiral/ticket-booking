<?php

declare(strict_types=1);

namespace App\Workflow\ReserveTicket;

use App\Application\Command\CancelTicketCommand;
use App\Application\Command\ReserveTicketCommand;

interface ReserveTicketHandlerInterface
{
    /**
     * @return ReserveTicketWorkflowInterface
     */
    public function reserve(ReserveTicketCommand $command): object;

    public function cancel(CancelTicketCommand $command): void;
}
