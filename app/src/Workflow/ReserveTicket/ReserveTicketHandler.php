<?php

declare(strict_types=1);

namespace App\Workflow\ReserveTicket;

use App\Command\ReserveTicketCommand;
use Psr\Log\LoggerInterface;
use Spiral\Cqrs\Attribute\CommandHandler;
use Spiral\TemporalBridge\WorkflowManagerInterface;
use Spiral\TemporalBridge\Workflow\RunningWorkflow;
use Temporal\Api\Enums\V1\WorkflowIdReusePolicy;
use Temporal\Exception\Client\WorkflowExecutionAlreadyStartedException;

class ReserveTicketHandler implements ReserveTicketHandlerInterface
{
    public function __construct(
        private readonly WorkflowManagerInterface $manager,
        private readonly LoggerInterface $logger
    ) {
    }

    #[CommandHandler]
    public function reserve(ReserveTicketCommand $command): RunningWorkflow
    {
        $workflow = $this->manager
            ->create(ReserveTicketWorkflowInterface::class);
        // $workflow->assignId(
        //     'operation-id',
        //     WorkflowIdReusePolicy::WORKFLOW_ID_REUSE_POLICY_ALLOW_DUPLICATE_FAILED_ONLY
        // );

        // $workflow->withWorkflowRunTimeout(\Carbon\CarbonInterval::minutes(10))
        //    ->withWorkflowTaskTimeout(\Carbon\CarbonInterval::minute())
        //    ->withWorkflowExecutionTimeout(\Carbon\CarbonInterval::minutes(5));

        // $workflow->maxRetryAttempts(5)
        //      ->backoffRetryCoefficient(1.5)
        //      ->initialRetryInterval(\Carbon\CarbonInterval::seconds(5))
        //      ->maxRetryInterval(\Carbon\CarbonInterval::seconds(20));
        try {
            $run = $workflow->run(
                $command->reservationId->toString(),
                $command->screeningId,
                $command->reservationTypeId,
                $command->seatIds
            );

            $this->logger->info('Workflow [ReserveTicket] has been run', [
                'id' => $run->getExecution()->getID(),
            ]);

            return $run;
        } catch (WorkflowExecutionAlreadyStartedException $e) {
            $this->logger->error('Workflow has been already started.', [
                'name' => $workflow->getWorkflowType(),
            ]);

            throw $e;
        }
    }
}
