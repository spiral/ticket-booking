<?php

declare(strict_types=1);

namespace Database\Factory;

use App\Entity\Screening;
use App\ValueObject\Money;
use Spiral\DatabaseSeeder\Factory\AbstractFactory;

class ScreeningFactory extends AbstractFactory
{
    /**
     * Returns a fully qualified database entity class name
     */
    public function entity(): string
    {
        return Screening::class;
    }

    /**
     * Returns array with generation rules
     */
    public function definition(): array
    {
        return [
            'movie' => fn() => MovieFactory::new()->createOne(),
            'auditorium' => fn() => AuditoriumFactory::new()->createOne(),
            'startsAt' => $this->faker->dateTime(),
            'price' => new Money($this->faker->numberBetween(3, 10)),
        ];
    }

    public function makeEntity(array $definition): object
    {
        return new Screening(
            $definition['movie'],
            $definition['auditorium'],
            $definition['startsAt'],
            $definition['price'],
        );
    }
}
