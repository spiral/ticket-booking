<?php

declare(strict_types=1);

namespace Database\Factory;

use App\Entity\Auditorium;
use Spiral\DatabaseSeeder\Factory\AbstractFactory;

class ReservedSeatFactory extends AbstractFactory
{
    /**
     * Returns a fully qualified database entity class name
     */
    public function entity(): string
    {
        return Auditorium\ReservedSeat::class;
    }

    /**
     * Returns array with generation rules
     */
    public function definition(): array
    {
        return [
            'seat' => fn() => SeatFactory::new()->createOne(),
        ];
    }

    public function makeEntity(array $definition): object
    {
        return new Auditorium\ReservedSeat(
            $definition['seat'],
            $definition['reservation']
        );
    }
}
