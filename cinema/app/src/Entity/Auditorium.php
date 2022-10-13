<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Auditorium\Seat;
use App\Repository\AuditoriumRepositoryInterface;
use App\Repository\Postgres\AuditoriumRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasMany;

#[Entity(
    //repository: AuditoriumRepositoryInterface::class,
    repository: AuditoriumRepository::class,
    table: 'auditoriums'
)]
class Auditorium
{
    #[Column(type: 'bigPrimary', name: 'id')]
    private int $id;

    #[HasMany(
        target: Seat::class,
        innerKey: 'id',
        outerKey: 'auditorium_id',
        orderBy: ['row' => 'asc', 'number' => 'asc']
    )]
    private array $seats = [];

    public function __construct(
        #[Column(type: 'string', name: 'name')]
        private string $name,
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

    /**
     * @return Seat[]
     */
    public function getSeats(): array
    {
        return $this->seats;
    }

    public function getTotalSeats(): int
    {
        return \count($this->seats);
    }
}
