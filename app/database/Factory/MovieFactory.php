<?php

declare(strict_types=1);

namespace Database\Factory;

use App\Entity\Movie;
use Spiral\DatabaseSeeder\Factory\AbstractFactory;

class MovieFactory extends AbstractFactory
{
    /**
     * Returns a fully qualified database entity class name
     */
    public function entity(): string
    {
        return Movie::class;
    }

    /**
     * Returns array with generation rules
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'duration' => $this->faker->numberBetween(60, 240),
        ];
    }
}
