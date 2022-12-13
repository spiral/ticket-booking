<?php

declare(strict_types=1);

namespace App\Application\Query;

use Spiral\Cqrs\QueryInterface;

final class GetUserQuery implements QueryInterface
{
    public function __construct(
        public readonly int $userId
    ) {
    }
}
