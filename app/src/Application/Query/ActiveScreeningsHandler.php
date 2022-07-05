<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Repository\ScreeningRepositoryInterface;
use Spiral\Cqrs\Attribute\QueryHandler;

final class ActiveScreeningsHandler
{
    public function __construct(
        private readonly ScreeningRepositoryInterface $screenings
    ){
    }

    #[QueryHandler]
    public function __invoke(ActiveScreeningsQuery $query): array
    {
        return $this->screenings->findAllActive();
    }
}
