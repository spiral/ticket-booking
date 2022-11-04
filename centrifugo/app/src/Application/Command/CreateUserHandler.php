<?php

declare(strict_types=1);

namespace App\Application\Command;

use Spiral\Cqrs\Attribute\CommandHandler;
use Spiral\Shared\GRPC\RequestContext;
use Spiral\Shared\Services\Users\v1\DTO\RegisterRequest;
use Spiral\Shared\Services\Users\v1\UsersServiceInterface;

final class CreateUserHandler
{
    public function __construct(
        private readonly UsersServiceInterface $usersService
    ) {
    }

    #[CommandHandler]
    public function __invoke(CreateUserCommand $command): array
    {
        $response = $this->usersService->Register(new RequestContext(), new RegisterRequest([
            'email' => $command->email->getValue(),
            'password' => $command->password
        ]));

        return [
            'id' => $response->getUser()->getId(),
            'email' => $response->getUser()->getEmail(),
            'roles' => $response->getUser()->getRoles(),
        ];
    }
}
