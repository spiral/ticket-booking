<?php

declare(strict_types=1);

namespace App\Specification;

use App\Exception\PasswordIncorrectException;
use App\Exception\UserAlreadyExistsException;
use Spiral\Translator\TranslatorInterface;
use Webmozart\Assert\Assert;

final class PasswordIsSecureSpecification extends AbstractSpecification
{
    public function __construct(
        private TranslatorInterface $translator
    ) {
    }

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
        Assert::minLength($value, 8, $this->translator->trans('Password must be 8 characters or more!'));
        Assert::regex($value, '#[0-9]+#', $this->translator->trans('Password must include at least one number!'));
        Assert::regex($value, '#[a-zA-Z]+#', $this->translator->trans('Password must include at least one letter!'));

        return true;
    }
}
