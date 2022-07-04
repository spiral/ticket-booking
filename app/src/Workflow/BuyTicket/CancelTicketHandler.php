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

class CancelTicketHandler implements CancelTicketHandlerInterface
{
    public function __construct(
        private WorkflowManagerInterface $manager,
        private LoggerInterface $logger,
    ) {
    }

    #[CommandHandler]
    public function buy(BuyTicketCommand $command)
    {
        $workflow = $this->manager->getById('checkout-'.$command->reservationId);

        try {
            $workflow->signal('pay');

            return $workflow->getResult();
        } catch (WorkflowExecutionAlreadyStartedException $e) {
            $this->logger->error('Workflow has been already started.', [
                'name' => $workflow->getWorkflowType()
            ]);

            throw $e;
        }
    }
}
