<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\ValueObject\Email;
use Cycle\ORM\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    /** @return ?User */
    public function findByPK(mixed $id): ?object;

    public function findOneByEmail(Email $email): ?User;

    public function existEmail(Email $email): bool;
}
