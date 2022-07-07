<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\Postgres\UserRepository;
use App\ValueObject\Email;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\Index;
use Spiral\Security\ActorInterface;

#[Entity(
    repository: UserRepository::class
)]
#[Index(columns: ['email'], unique: true)]
class User implements ActorInterface
{
    #[Column(type: 'primary', name: 'id')]
    private int $id;

    public function __construct(
        #[Column(type: 'string', name: 'email', typecast: 'email')]
        private Email $email,
        #[Column(type: 'string', name: 'password')]
        private string $password,
        #[Column(type: 'json', name: 'roles', typecast: 'json')]
        private array $roles
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        if (!\is_array(Role::USER->value)) {
            $this->roles[] = Role::USER->value;
        }

        return $this->roles;
    }
}
