<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Screening;
use Cycle\ORM\RepositoryInterface;

interface ScreeningRepositoryInterface extends RepositoryInterface
{
    /**
     * @return iterable<Screening>
     */
    public function findAllActive(): iterable;

    public function getByPK(int $id): Screening;
}
