<?php

declare(strict_types=1);

namespace App\UI\Web\Controller\Api\Cinema;

use App\Application\Query\ScreeningByIdQuery;
use Spiral\Cqrs\QueryBusInterface;
use Spiral\Router\Annotation\Route;

final class ShowScreeningByIdAction
{
    #[Route('/api/cinema/screening/<id:\d+>', name: 'api.cinema.screening', methods: 'GET', group: 'api')]
    public function __invoke(string $id, QueryBusInterface $queryBus): array
    {
        return [
            'data' => $queryBus->ask(new ScreeningByIdQuery((int) $id)),
        ];
    }
}
