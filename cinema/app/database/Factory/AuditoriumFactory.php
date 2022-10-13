<?php

declare(strict_types=1);

namespace Database\Factory;

use App\Entity\Auditorium;
use Spiral\DatabaseSeeder\Factory\AbstractFactory;

class AuditoriumFactory extends AbstractFactory
{
    /**
     * Returns a fully qualified database entity class name
     */
    public function entity(): string
    {
        return Auditorium::class;
    }

    /**
     * Returns array with generation rules
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence,
        ];
    }

    public function makeEntity(array $definition): object
    {
        return new Auditorium(
            $definition['name']
        );
    }
}
