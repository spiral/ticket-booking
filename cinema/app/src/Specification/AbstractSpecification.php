<?php

declare(strict_types=1);

namespace App\Specification;

abstract class AbstractSpecification
{
    abstract public function isSatisfiedBy(mixed $value): bool;
}
