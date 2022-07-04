<?php

declare(strict_types=1);

namespace App\Repository\Postgres\Reservation;

use App\Entity\Reservation\Type;
use App\Exception\EntityNotFoundException;
use App\Repository\Reservation\TypeRepositoryInterface;
use Cycle\ORM\Select\Repository;

final class TypeRepository extends Repository implements TypeRepositoryInterface
{

    public function getByPK(int $reservationTypeId): Type
    {
        $type = $this->findByPK($reservationTypeId);

        if (! $type) {
            throw new EntityNotFoundException();
        }

        return $type;
    }
}
