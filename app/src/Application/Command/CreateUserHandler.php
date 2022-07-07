<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Entity\User;
use App\Exception\PasswordIncorrectException;
use App\Exception\UserAlreadyExistsException;
use App\Security\PasswordHasher;
use App\Specification\PasswordIsSecureSpecification;
use App\Specification\UniqueEmailSpecification;
use Cycle\ORM\EntityManagerInterface;
use Spiral\Cqrs\Attribute\CommandHandler;

final class CreateUserHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PasswordHasher $passwordHasher,
        private readonly UniqueEmailSpecification $uniqueEmailSpecification,
        private readonly PasswordIsSecureSpecification $passwordIsSecureSpecification
    ) {
    }

    /**
     * @throws UserAlreadyExistsException
     * @throws PasswordIncorrectException
     */
    #[CommandHandler]
    public function __invoke(CreateUserCommand $command): void
    {
        $this->passwordIsSecureSpecification->isSecure($command->password);
        $this->uniqueEmailSpecification->isUnique($command->email);

        $this->entityManager->persist(
            $user = $this->makeUser($command)
        );

        $this->entityManager->run();
    }

    private function makeUser(CreateUserCommand $command): User
    {
        return new User(
            $command->email,
            $this->passwordHasher->hash($command->password),
            $command->roles
        );
    }
}
