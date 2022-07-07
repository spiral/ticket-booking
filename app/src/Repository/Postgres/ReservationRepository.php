<?php

declare(strict_types=1);

namespace App\Repository\Postgres;

use App\Entity\Reservation;
use App\Exception\EntityNotFoundException;
use App\Repository\ReservationRepositoryInterface;
use Cycle\ORM\Select\Repository;

final class ReservationRepository extends Repository implements ReservationRepositoryInterface
{
    public function getByPK(string $id): Reservation
    {
        $reservation = $this->findByPK($id);

        if (! $reservation) {
            throw new EntityNotFoundException();
        }

        return $reservation;
    }

    public function findByUserId(int $userId): iterable
    {
        return $this->findAll(['user_id' => $userId]);
    }

    public function hasByPK(string $id): bool
    {
        return $this->select()->where(['uuid' => $id])->count() === 1;
    }
}
