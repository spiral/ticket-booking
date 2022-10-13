<?php

declare(strict_types=1);

namespace Database\Factory;

use App\Entity\Reservation;
use Ramsey\Uuid\Uuid;
use Spiral\DatabaseSeeder\Factory\AbstractFactory;

class ReservationFactory extends AbstractFactory
{
    /**
     * Returns a fully qualified database entity class name
     */
    public function entity(): string
    {
        return Reservation::class;
    }

    /**
     * Returns array with generation rules
     */
    public function definition(): array
    {
        return [
            'screening' => fn() => ScreeningFactory::new()->createOne(),
            'type' => fn() => ReservationTypeFactory::new()->createOne(),
        ];
    }

    public function makeEntity(array $definition): object
    {
        return new Reservation(
            Uuid::uuid4()->toString(),
            $definition['screening'],
            $definition['type'],
            $definition['user']
        );
    }
}
