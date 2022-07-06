<?php

declare(strict_types=1);

namespace App\Security;

use Spiral\Core\Container\SingletonInterface;

final class PasswordHasher implements SingletonInterface
{
    public function isPasswordValid(string $password, string $hash): bool
    {
        return \password_verify($password, $hash);
    }

    public function hash(string $password): string
    {
        return \password_hash($password, PASSWORD_DEFAULT);
    }
}
