<?php

declare(strict_types=1);

namespace App\Specification;

use App\Exception\UserAlreadyExistsException;
use App\Repository\UserRepositoryInterface;
use App\ValueObject\Email;
use Spiral\Translator\TranslatorInterface;

final class UniqueEmailSpecification extends AbstractSpecification
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private TranslatorInterface $translator
    ) {
    }

    /**
     * @throws UserAlreadyExistsException
     */
    public function isUnique(Email $email): bool
    {
        return $this->isSatisfiedBy($email);
    }

    /**
     * @psalm-param Email $value
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function isSatisfiedBy(mixed $value): bool
    {
        if ($this->userRepository->existEmail($value)) {
            throw new UserAlreadyExistsException(
                $this->translator->trans(\sprintf('User with Email `%s` already exists.', $value))
            );
        }

        return true;
    }
}
