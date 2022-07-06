<?php

declare(strict_types=1);

namespace App\Security;

use App\Exception\PasswordIncorrectException;
use App\Exception\UserNotFoundException;
use App\Repository\UserRepositoryInterface;
use App\ValueObject\Credentials;
use Spiral\Auth\AuthScope;
use Spiral\Auth\TokenStorageInterface;
use Spiral\Translator\TranslatorInterface;

final class Authenticator
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly AuthScope $authScope,
        private readonly UserRepositoryInterface $userRepository,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function authenticate(Credentials $credentials): void
    {
        $user = $this->userRepository->findOneByEmail($credentials->email);

        if ($user === null) {
            throw new UserNotFoundException(
                $this->translator->trans('User with this Email not found.')
            );
        }

        $hasher = new PasswordHasher();

        if (!$hasher->isPasswordValid($credentials->plainPassword, $user->getPassword())) {
            throw new PasswordIncorrectException(
                $this->translator->trans('The password is not valid. Check the password and try again.')
            );
        }

        $token = $this->tokenStorage->create(['userID' => $user->getId()]);

        $this->authScope->start($token, 'cookie');
    }

    public function close(string $token): void
    {
        if ($this->authScope->getToken() === null || $this->authScope->getToken()->getID() !== $token) {
            return;
        }

        $this->authScope->close();
    }
}
