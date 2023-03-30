<?php

declare(strict_types=1);

namespace App\Services\Mappers;

use Spiral\Shared\Services\Common\v1\DTO\Money;

final class MoneyFactory
{
    public static function fromMoney(\App\ValueObject\Money $money): Money
    {
        return self::create($money->getValue());
    }

    public static function create(int $amount, string $currency = '$'): Money
    {
        return new Money([
            'amount' => $amount,
            'currency' => $currency,
        ]);
    }
}
