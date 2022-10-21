<?php

declare(strict_types=1);

namespace App\UI\Web\Controller\Api\User;

use App\Application\Query\UserTicketsQuery;
use Spiral\Auth\AuthScope;
use Spiral\Cqrs\QueryBusInterface;
use Spiral\Router\Annotation\Route;

final class ShowTicketsAction
{
    #[Route('/api/profile/tickets', name: 'api.profile.tickets', methods: 'GET', group: 'api_personal')]
    public function __invoke(AuthScope $authScope, QueryBusInterface $queryBus): array
    {
        return [
            'data' => $queryBus->ask(new UserTicketsQuery($authScope->getActor()->id)),
        ];
    }
}
