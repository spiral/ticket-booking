<?php

declare(strict_types=1);

namespace App\Specification;

use App\Exception\PasswordIncorrectException;
use App\Exception\UserAlreadyExistsException;
use Webmozart\Assert\Assert;

final class PasswordIsSecureSpecification extends AbstractSpecification
{
    /**
     * @throws UserAlreadyExistsException
     */
    public function isSecure(string $plainPassword): bool
    {
        return $this->isSatisfiedBy($plainPassword);
    }

    /**
     * @psalm-param string $value
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function isSatisfiedBy(mixed $value): bool
    {
        Assert::minLength($value, 8, 'Password must be 8 characters or more!');
        Assert::regex($value, '#[0-9]+#', 'Password must include at least one number!');
        Assert::regex($value, '#[a-zA-Z]+#', 'Password must include at least one letter!');

        return true;
    }
}
