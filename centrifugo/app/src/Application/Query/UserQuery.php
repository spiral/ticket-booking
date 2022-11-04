<?php

declare(strict_types=1);

namespace App\Application\Query;

use Spiral\Cqrs\QueryInterface;

final class UserQuery implements QueryInterface
{
    public function __construct(
        public readonly int $userId
    ) {
    }
}
