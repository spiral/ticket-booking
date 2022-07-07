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
        private readonly UserRepositoryInterface $userRepository,
        private readonly TranslatorInterface $translator
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
                $this->translator->trans('User with email {email} already exists.', ['email' => $value])
            );
        }

        return true;
    }
}
