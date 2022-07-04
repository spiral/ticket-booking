<?php

declare(strict_types=1);

namespace App\Bootloader;

use App\Workflow\BuyTicket\CancelTicketHandler;
use App\Workflow\BuyTicket\CancelTicketHandlerInterface;
use App\Workflow\ReserveTicket\ReserveTicketHandler;
use App\Workflow\ReserveTicket\ReserveTicketHandlerInterface;
use Spiral\Boot\Bootloader\Bootloader;

final class WorkflowBootloader extends Bootloader
{
    protected const BINDINGS = [
        ReserveTicketHandlerInterface::class => ReserveTicketHandler::class,
        CancelTicketHandlerInterface::class => CancelTicketHandler::class,
    ];
}
