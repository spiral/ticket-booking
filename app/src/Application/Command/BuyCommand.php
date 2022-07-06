<?php

declare(strict_types=1);

namespace App\Application\Command;

use Spiral\Cqrs\CommandInterface;

final class BuyCommand implements CommandInterface
{
    public function __construct(
        public readonly string $reservationId
    ) {
    }
}
