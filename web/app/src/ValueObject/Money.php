<?php

declare(strict_types=1);

namespace Spiral\SharedValueObjects;

final class Money implements \Stringable, \JsonSerializable
{
    public function __construct(int $amount)
    {
        $this->value = $amount;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
