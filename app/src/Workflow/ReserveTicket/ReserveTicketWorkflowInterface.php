<?php

declare(strict_types=1);

namespace App\Workflow\ReserveTicket;

use Temporal\Workflow\QueryMethod;
use Temporal\Workflow\SignalMethod;
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[WorkflowInterface]
interface ReserveTicketWorkflowInterface
{
    #[WorkflowMethod]
    public function reserve(
        string $reservationId,
        int $screeningId,
        int $reservationTypeId,
        array $seatIds,
    );

    #[SignalMethod]
    public function cancel(): \Generator;

    #[SignalMethod]
    public function pay(): \Generator;

    #[QueryMethod]
    public function isExpired(): bool;
}
