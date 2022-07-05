<?php

declare(strict_types=1);

namespace App\Workflow\ReserveTicket;

use App\Application\Command\CancelTicketCommand;
use App\Application\Command\ReserveTicketCommand;
use Ramsey\Uuid\UuidInterface;
use Spiral\Cqrs\Attribute\CommandHandler;
use Spiral\TemporalBridge\WorkflowManagerInterface;
use Temporal\Api\Enums\V1\WorkflowIdReusePolicy;

class ReserveTicketHandler implements ReserveTicketHandlerInterface
{
    public function __construct(
        private readonly WorkflowManagerInterface $manager
    ) {
    }

    #[CommandHandler]
    public function reserve(ReserveTicketCommand $command): UuidInterface
    {
        $workflow = $this->manager
            ->create(ReserveTicketWorkflowInterface::class);

        $workflow->assignId(
            $id = $this->generateWorkflowId($command),
            WorkflowIdReusePolicy::WORKFLOW_ID_REUSE_POLICY_REJECT_DUPLICATE
        );

        $workflow->maxRetryAttempts(1);

        $workflow->run(
            $command->reservationId->toString(),
            $command->screeningId,
            $command->reservationTypeId,
            $command->seatIds
        );

        return $command->reservationId;
    }

    #[CommandHandler]
    public function cancel(CancelTicketCommand $command): void
    {
        $workflow = $this->manager
            ->getById($this->generateWorkflowId($command));

        $workflow->maxRetryAttempts(0);

        $workflow->signal('cancel');
    }

    private function generateWorkflowId(ReserveTicketCommand|CancelTicketCommand $command): string
    {
        return 'checkout-'.$command->reservationId->toString();
    }
}
