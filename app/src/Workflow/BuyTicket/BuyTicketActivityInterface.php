<?php

declare(strict_types=1);

namespace App\Workflow\BuyTicket;

use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

#[ActivityInterface(prefix: 'BuyTicket.')]
interface BuyTicketActivityInterface
{
    #[ActivityMethod]
    public function buy(string $reservationId);
}
