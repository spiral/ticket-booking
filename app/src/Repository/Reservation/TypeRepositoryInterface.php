<?php

declare(strict_types=1);

namespace App\Repository\Reservation;

use App\Entity\Reservation\Type;
use Cycle\ORM\RepositoryInterface;

interface TypeRepositoryInterface extends RepositoryInterface
{
    public function getByPK(int $reservationTypeId): Type;
}
