<?php

declare(strict_types=1);

namespace App\Services\Mappers;

use Spiral\Shared\Services\Cinema\v1\DTO\Movie;

final class MovieFactory
{
    public static function fromMovieEntity(\App\Entity\Movie $movie): Movie
    {
        return new Movie([
            'id' => $movie->getId(),
            'title' => $movie->getTitle(),
            'description' => $movie->getDescription(),
            'duration' => $movie->getDuration()->minutes,
        ]);
    }
}
