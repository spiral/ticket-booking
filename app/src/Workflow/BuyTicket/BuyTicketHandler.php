<?php

declare(strict_types=1);

namespace App\Workflow\BuyTicket;

use App\Application\Command\BuyTicketCommand;
use Psr\Log\LoggerInterface;
use Spiral\TemporalBridge\WorkflowManagerInterface;
use Temporal\Exception\Client\WorkflowExecutionAlreadyStartedException;

class BuyTicketHandler implements BuyTicketHandlerInterface
{
    public function __construct(
        private WorkflowManagerInterface $manager,
        private LoggerInterface $logger,
    ) {
    }

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
