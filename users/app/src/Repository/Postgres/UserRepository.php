<?php

declare(strict_types=1);

namespace App\Repository\Postgres;

use App\Entity\User;
use App\Exception\EntityNotFoundException;
use App\Repository\UserRepositoryInterface;
use App\ValueObject\Email;
use Cycle\ORM\Select\Repository;

final class UserRepository extends Repository implements UserRepositoryInterface
{
    public function findOneByEmail(Email $email): ?User
    {
        /** @var ?User $user */
        $user = $this->findOne(['email' => $email]);

        return $user;
    }

    public function getByPK(int $id): User
    {
        /** @var ?User $user */
        $user = $this->findByPK($id);

        if (!$user) {
            throw new EntityNotFoundException();
        }

        return $user;
    }

    public function getOneByEmail(Email $email): User
    {
        /** @var ?User $user */
        $user = $this->findOneByEmail($email);

        if (!$user) {
            throw new EntityNotFoundException();
        }

        return $user;
    }

    public function existEmail(Email $email): bool
    {
        return $this->select()->where(['email' => $email->getValue()])->count() > 0;
    }
}
