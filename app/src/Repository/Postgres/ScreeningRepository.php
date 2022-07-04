<?php

declare(strict_types=1);

namespace App\Repository\Postgres;

use App\Entity\Screening;
use App\Exception\EntityNotFoundException;
use App\Repository\ScreeningRepositoryInterface;
use Cycle\ORM\Select\Repository;

final class ScreeningRepository extends Repository implements ScreeningRepositoryInterface
{
    public function findAllActive(): iterable
    {
        return $this->findAll(['starts_at' => ['>=' => new \DateTimeImmutable()]]);
    }

    public function getByPK(int $id): Screening
    {
        $screening = $this->findByPK($id);

        if (! $screening) {
            throw new EntityNotFoundException();
        }

        return $screening;
    }
}
