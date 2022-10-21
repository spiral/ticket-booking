<?php

declare(strict_types=1);

namespace App\UI\Web\Controller\Api\Cinema;

use App\Application\Command\BuyTicketCommand;
use App\UI\Web\Request\BuyRequest;
use Ramsey\Uuid\Uuid;
use Spiral\Auth\AuthScope;
use Spiral\Cqrs\CommandBusInterface;
use Spiral\Router\Annotation\Route;

final class PurchaseTicketsAction
{
    #[Route('/api/tickets/purchase', name: 'api.cinema.purchase', methods: 'POST', group: 'api_personal')]
    public function __invoke(
        BuyRequest $request,
        CommandBusInterface $commandBus,
        AuthScope $authScope
    ): array {
        $command = new BuyTicketCommand(Uuid::fromString($request->reservationId));

        try {
            return [
                'data' => (array)$commandBus->dispatch($command),
            ];
        } catch (\Throwable $e) {
            return ['errors' => [$e->getMessage()]];
        }
    }
}
