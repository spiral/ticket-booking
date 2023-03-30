<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\ValueObject\Money;
use Spiral\Cqrs\CommandInterface;

final class ChargeMoneyCommand implements CommandInterface
{
    public function __construct(
        public readonly string $description,
        public readonly string $paymentMethod,
        public readonly string $email,
        public readonly Money $money
    ) {
    }
}
