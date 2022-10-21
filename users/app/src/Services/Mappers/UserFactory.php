<?php

declare(strict_types=1);

namespace App\Services\Mappers;

use Spiral\Shared\Services\Users\v1\DTO\User;

final class UserFactory
{
    public static function fromUserEntity(\App\Entity\User $user): User
    {
        return new User([
            'id' => $user->getId(),
            'email' => $user->getEmail()->getValue(),
            'roles' => $user->getRoles(),
        ]);
    }
}
