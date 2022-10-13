<?php

declare(strict_types=1);

namespace Database\Factory;

use App\Entity\Auditorium;
use Spiral\DatabaseSeeder\Factory\AbstractFactory;

class SeatFactory extends AbstractFactory
{
    /**
     * Returns a fully qualified database entity class name
     */
    public function entity(): string
    {
        return Auditorium\Seat::class;
    }

    /**
     * Returns array with generation rules
     */
    public function definition(): array
    {
        return [
            'row' => $this->faker->numberBetween(),
            'number' => $this->faker->numberBetween(),
        ];
    }

    public function makeEntity(array $definition): object
    {
        return new Auditorium\Seat(
            $definition['auditorium'],
            $definition['row'],
            $definition['number']
        );
    }
}
