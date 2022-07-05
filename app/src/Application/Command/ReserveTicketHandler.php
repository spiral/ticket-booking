<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Workflow\ReserveTicket\ReserveTicketHandlerInterface;
use App\Workflow\ReserveTicket\SeatsReservationChecker;
use Spiral\Cqrs\Attribute\CommandHandler;

final class ReserveTicketHandler
{
    public function __construct(
        private readonly ReserveTicketHandlerInterface $workflow,
        private readonly SeatsReservationChecker $checker
    ) {
    }

    #[CommandHandler]
    public function __invoke(ReserveTicketCommand $command): void
    {
        $this->checker->checkAvailability($command->screeningId, $command->seatIds);

        $this->workflow->reserve($command);
    }
}
