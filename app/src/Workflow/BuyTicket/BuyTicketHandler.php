<?php

declare(strict_types=1);

namespace App\Workflow\BuyTicket;

use App\Command\BuyTicketCommand;
use Psr\Log\LoggerInterface;
use Spiral\Cqrs\Attribute\CommandHandler;
use Spiral\TemporalBridge\WorkflowManagerInterface;
use Spiral\TemporalBridge\Workflow\RunningWorkflow;
use Temporal\Api\Enums\V1\WorkflowIdReusePolicy;
use Temporal\Exception\Client\WorkflowExecutionAlreadyStartedException;

class BuyTicketHandler implements BuyTicketHandlerInterface
{
    public function __construct(
        private WorkflowManagerInterface $manager,
        private LoggerInterface $logger,
    ) {
    }

    #[CommandHandler]
    public function buy(BuyTicketCommand $command): RunningWorkflow
    {
        $workflow = $this->manager
            ->create(BuyTicketWorkflowInterface::class);

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
            $run = $workflow->run($command->reservationId);

            $this->logger->info('Workflow [BuyTicket] has been run', [
                'id' => $run->getExecution()->getID()
            ]);

            return $run;
        } catch (WorkflowExecutionAlreadyStartedException $e) {
            $this->logger->error('Workflow has been already started.', [
                'name' => $workflow->getWorkflowType()
            ]);

            throw $e;
        }
    }
}
