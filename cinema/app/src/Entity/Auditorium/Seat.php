<?php

declare(strict_types=1);

namespace App\Entity\Auditorium;

use App\Entity\Auditorium;
use App\Repository\Auditorium\SeatRepositoryInterface;
use App\Repository\Postgres\Auditorium\SeatRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Table\Index;

#[Entity(
    //repository: SeatRepositoryInterface::class,
    repository: SeatRepository::class,
    table: 'auditorium_seats'
)]
#[Index(columns: ['row', 'number', 'auditorium_id'], unique: true)]
class Seat
{
    #[Column(type: 'bigPrimary', name: 'id')]
    private int $id;

    public function __construct(
        #[BelongsTo(target: Auditorium::class)]
        private Auditorium $auditorium,
        #[Column(type: 'integer', name: 'row')]
        private int $row,
        #[Column(type: 'integer', name: 'number')]
        private int $number
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuditorium(): Auditorium
    {
        return $this->auditorium;
    }

    public function getRow(): int
    {
        return $this->row;
    }

    public function getNumber(): int
    {
        return $this->number;
    }
}
