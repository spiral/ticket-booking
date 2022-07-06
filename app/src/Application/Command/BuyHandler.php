<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Workflow\BuyTicket\BuyTicketWorkflowInterface;
use Spiral\Cqrs\Attribute\CommandHandler;

final class BuyHandler
{
    public function __construct(
        private readonly BuyTicketWorkflowInterface $workflow
    ) {
    }

    #[CommandHandler]
    public function __invoke(BuyCommand $command): void
    {
        // TODO add specifications

        $this->workflow->buy($command->reservationId);
    }
}
