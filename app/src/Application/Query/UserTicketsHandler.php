<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Repository\ReservationRepositoryInterface;
use Spiral\Cqrs\Attribute\QueryHandler;

final class UserTicketsHandler
{
    public function __construct(
        private readonly ReservationRepositoryInterface $tickets
    ){
    }

    #[QueryHandler]
    public function __invoke(UserTicketsQuery $query): iterable
    {
        return $this->tickets->findByUserId($query->userId);
    }
}
