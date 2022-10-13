<?php

declare(strict_types=1);

namespace App\ValueObject;

final class Credentials
{
    public function __construct(
        public readonly Email $email,
        public readonly string $plainPassword
    ) {
    }
}
