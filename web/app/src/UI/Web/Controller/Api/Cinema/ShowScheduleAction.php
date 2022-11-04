<?php

declare(strict_types=1);

namespace App\UI\Web\Controller\Api\Cinema;

use App\Application\Query\ActiveScreeningsQuery;
use App\Event\ControllerRun;
use Psr\EventDispatcher\EventDispatcherInterface;
use Spiral\Cqrs\QueryBusInterface;
use Spiral\Router\Annotation\Route;

final class ShowScheduleAction
{
    #[Route('/api/cinema/schedule', name: 'api.cinema.schedule', methods: 'GET', group: 'api')]
    public function __invoke(
        QueryBusInterface $queryBus
    ): array {
        return [
            'data' => $queryBus->ask(new ActiveScreeningsQuery()),
        ];
    }
}
