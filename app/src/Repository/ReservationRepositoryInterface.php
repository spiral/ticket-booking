<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Reservation;
use Cycle\ORM\RepositoryInterface;

interface ReservationRepositoryInterface extends RepositoryInterface
{
    public function getByPK(string $id): Reservation;
}
