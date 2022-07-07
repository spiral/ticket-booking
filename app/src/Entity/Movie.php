<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MovieRepositoryInterface;
use App\Repository\Postgres\MovieRepository;
use App\ValueObject\Duration;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\Index;

#[Entity(
    //repository: MovieRepositoryInterface::class,table:
    repository: MovieRepository::class
)]
#[Index(columns: ['title'], unique: true)]
class Movie
{
    #[Column(type: 'bigPrimary', name: 'id')]
    private int $id;

    public function __construct(
        #[Column(type: 'string', name: 'title')]
        private string $title,
        #[Column(type: 'text', name: 'description')]
        private string $description,
        #[Column(type: 'integer', name: 'duration', typecast: 'duration')]
        private Duration $duration
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDuration(): Duration
    {
        return $this->duration;
    }
}
