<?php

declare(strict_types=1);

namespace App\Workflow\BuyTicket;

use App\Application\Command\BuyTicketCommand;

interface BuyTicketHandlerInterface
{
    public function buy(BuyTicketCommand $command);
}
