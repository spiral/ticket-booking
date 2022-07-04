<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Auditorium\ReservedSeat;
use App\Repository\Postgres\ScreeningRepository;
use App\Repository\ScreeningRepositoryInterface;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\Annotated\Annotation\Table\Index;

#[Entity(
    // repository: ScreeningRepositoryInterface::class,
    repository: ScreeningRepository::class
)]
#[Index(columns: ['starts_at', 'movie_id', 'auditorium_id'], unique: true)]
class Screening
{
    #[Column(type: 'bigPrimary', name: 'id')]
    private int $id;

    #[HasMany(target: ReservedSeat::class)]
    private array $reservedSeats = [];

    public function __construct(
        #[BelongsTo(target: Movie::class)]
        private Movie $movie,
        #[BelongsTo(target: Auditorium::class)]
        private Auditorium $auditorium,
        #[Column(type: 'datetime', name: 'starts_at')]
        private \DateTimeInterface $startsAt
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMovie(): Movie
    {
        return $this->movie;
    }

    public function getAuditorium(): Auditorium
    {
        return $this->auditorium;
    }

    public function getStartsAt(): \DateTimeInterface
    {
        return $this->startsAt;
    }

    /**
     * @return ReservedSeat[]
     */
    public function getReservedSeats(): array
    {
        return $this->reservedSeats;
    }

    public function isInProgress(): bool
    {
        return $this->startsAt < new \DateTimeImmutable();
    }
}
