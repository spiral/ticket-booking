<?php

declare(strict_types=1);

namespace App\ValueObject;

final class Duration implements \Stringable, \JsonSerializable
{
    public static function fromInterval(\DateInterval $interval): self
    {
        return new self($interval->i);
    }

    public function __construct(
        public readonly int $minutes
    ) {
    }

    public function __toString()
    {
        return (string)$this->minutes;
    }

    public function jsonSerialize(): int
    {
        return $this->minutes;
    }
}
