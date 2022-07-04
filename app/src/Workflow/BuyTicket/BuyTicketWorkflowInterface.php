<?php

declare(strict_types=1);

namespace App\Workflow\BuyTicket;

use Temporal\Workflow\QueryMethod;
use Temporal\Workflow\SignalMethod;
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[WorkflowInterface]
interface BuyTicketWorkflowInterface
{
    #[WorkflowMethod]
    public function buy(string $reservationId);
}
