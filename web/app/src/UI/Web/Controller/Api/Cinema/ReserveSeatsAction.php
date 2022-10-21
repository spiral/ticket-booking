<?php

declare(strict_types=1);

namespace App\UI\Web\Controller\Api\Cinema;

use App\Application\Command\ReserveTicketCommand;
use App\UI\Web\Request\ReserveTicketRequest;
use Spiral\Auth\AuthScope;
use Spiral\Cqrs\CommandBusInterface;
use Spiral\Router\Annotation\Route;

final class ReserveSeatsAction
{
    #[Route('/api/tickets/reserve', name: 'api.cinema.reserve', methods: 'POST', group: 'api_personal')]
    public function __invoke(
        ReserveTicketRequest $request,
        CommandBusInterface $commandBus,
        AuthScope $authScope
    ): array {
        $command = new ReserveTicketCommand(
            $request->screeningId,
            $request->reservationTypeId,
            $authScope->getActor()->id,
            $request->seatIds
        );

        try {
            return (array)$commandBus->dispatch($command);
        } catch (\Throwable $e) {
            return ['errors' => [$e->getMessage()]];
        }
    }
}
