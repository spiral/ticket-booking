<?php

declare(strict_types=1);

namespace App\Specification;

use App\Exception\PasswordIncorrectException;
use App\Exception\UserAlreadyExistsException;
use Spiral\Translator\TranslatorInterface;

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
        if (\strlen($value) < 8) {
            throw new PasswordIncorrectException($this->translator->trans('Password must be 8 characters or more!'));
        }

        if (!\preg_match('#[0-9]+#', $value)) {
            throw new PasswordIncorrectException(
                $this->translator->trans('Password must include at least one number!')
            );
        }

        if (!\preg_match('#[a-zA-Z]+#', $value)) {
            throw new PasswordIncorrectException(
                $this->translator->trans('Password must include at least one letter!')
            );
        }

        return true;
    }
}
