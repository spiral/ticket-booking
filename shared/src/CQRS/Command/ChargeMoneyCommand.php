<?php

declare(strict_types=1);

namespace Spiral\Shared\CQRS\Command;

use Spiral\Cqrs\CommandInterface;
use Spiral\Shared\ValueObjects\Money;

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
