<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Exception\EntityNotFoundException;
use App\Specification\ReservationIsExistsSpecification;
use App\Workflow\BuyTicket\BuyTicketHandlerInterface;
use Spiral\Cqrs\Attribute\CommandHandler;

final class BuyTicketHandler
{
    public function __construct(
        private readonly BuyTicketHandlerInterface $workflow,
        private readonly ReservationIsExistsSpecification $reservationIsExistsSpecification
    ) {
    }

    /**
     * @throws EntityNotFoundException
     */
    #[CommandHandler]
    public function __invoke(BuyTicketCommand $command): void
    {
        $this->reservationIsExistsSpecification->isExists($command->reservationId);

        $this->workflow->buy($command);
    }
}
