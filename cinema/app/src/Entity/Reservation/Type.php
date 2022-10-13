<?php

declare(strict_types=1);

namespace App\Entity\Reservation;

use App\Repository\Postgres\Reservation\TypeRepository;
use App\Repository\Reservation\TypeRepositoryInterface;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\Index;

#[Entity(
    //repository: TypeRepositoryInterface::class,
    repository: TypeRepository::class,
    table: 'reservation_types'
)]
#[Index(columns: ['name'], unique: true)]
class Type
{
    #[Column(type: 'bigPrimary', name: 'id')]
    private int $id;

    public function __construct(
        #[Column(type: 'string', name: 'name')]
        private string $name
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
