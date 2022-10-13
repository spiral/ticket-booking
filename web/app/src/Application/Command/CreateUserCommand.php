<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Entity\Role;
use App\ValueObject\Email;
use Spiral\Cqrs\CommandInterface;

final class CreateUserCommand implements CommandInterface
{
    public function __construct(
        public readonly Email $email,
        public readonly string $password,
        public readonly array $roles = [Role::USER]
    ) {
    }
}
