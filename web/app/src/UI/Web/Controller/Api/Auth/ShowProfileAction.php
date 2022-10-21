<?php

declare(strict_types=1);

namespace App\UI\Web\Controller\Api\Auth;

use App\Application\Query\UserQuery;
use Spiral\Auth\AuthScope;
use Spiral\Cqrs\QueryBusInterface;
use Spiral\Router\Annotation\Route;

final class ShowProfileAction
{
    #[Route('/api/auth/profile', name: 'api.auth.profile', methods: 'GET', group: 'api_personal')]
    public function __invoke(AuthScope $authScope, QueryBusInterface $queryBus): array
    {
        return [
            'data' => $queryBus->ask(new UserQuery($authScope->getActor()->id))
        ];
    }
}
