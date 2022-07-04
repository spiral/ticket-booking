<?php

declare(strict_types=1);

namespace App\Workflow\ReserveTicket;

use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

#[ActivityInterface(prefix: 'BuyTicket.')]
interface BuyTicketActivityInterface
{
    #[ActivityMethod]
    public function pay(string $reservationId);
}
