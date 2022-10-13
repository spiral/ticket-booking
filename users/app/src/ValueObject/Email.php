<?php

declare(strict_types=1);

namespace App\ValueObject;

final class Email implements \Stringable
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value) || !\filter_var($value, \FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Incorrect Email.');
        }
        $this->value = \mb_strtolower($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }

    public static function fromString(string $email): self
    {
        return new self($email);
    }
}
