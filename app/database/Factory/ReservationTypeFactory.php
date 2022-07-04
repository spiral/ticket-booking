<?php

declare(strict_types=1);

namespace Database\Factory;

use App\Entity\Reservation;
use Spiral\DatabaseSeeder\Factory\AbstractFactory;

class ReservationTypeFactory extends AbstractFactory
{
    /**
     * Returns a fully qualified database entity class name
     */
    public function entity(): string
    {
        return Reservation\Type::class;
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
}
