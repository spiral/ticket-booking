<?php

declare(strict_types=1);

namespace App\Entity;

use App\ValueObject\Duration;
use App\ValueObject\Money;
use Cycle\ORM\Parser\CastableInterface;
use Cycle\ORM\Parser\UncastableInterface;

final class ExtendedTypecast implements CastableInterface, UncastableInterface
{
    private array $rules = [];
    private array $availableRules = ['money', 'duration'];

    public function setRules(array $rules): array
    {
        foreach ($rules as $key => $rule) {
            if (\in_array($rule, $this->availableRules, true)) {
                unset($rules[$key]);
                $this->rules[$key] = $rule;
            }
        }

        return $rules;
    }

    public function cast(array $values): array
    {
        foreach ($this->rules as $column => $rule) {
            if (! isset($values[$column])) {
                continue;
            }

            $values[$column] = match ($rule) {
                'money' => new Money($values[$column]),
                'duration' => new Duration($values[$column]),
                default => $values[$column]
            };
        }

        return $values;
    }

    public function uncast(array $values): array
    {
        foreach ($this->rules as $column => $rule) {
            if (! isset($values[$column])) {
                continue;
            }

            $values[$column] = match ($rule) {
                default => (string)$values[$column]
            };
        }

        return $values;
    }
}
