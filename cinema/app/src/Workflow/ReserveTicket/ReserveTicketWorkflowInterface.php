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
        int $userId,
        array $seatIds,
    );

    #[SignalMethod]
    public function cancel(): \Generator;

    #[SignalMethod]
    public function pay(string $transactionId): \Generator;

    #[QueryMethod]
    public function getExpiresAt(): int;

    #[QueryMethod]
    public function isExpired(): bool;

    #[QueryMethod]
    public function expiresIn(): int;
}
