<?php

declare(strict_types=1);

namespace App\UI\Web\Controller\Api\Auth;

use App\Application\Command\CreateUserCommand;
use App\Security\Authenticator;
use App\UI\Web\Request\CredentialsRequest;
use Psr\Http\Message\ResponseInterface;
use Spiral\Cqrs\CommandBusInterface;
use Spiral\Http\ResponseWrapper;
use Spiral\Router\Annotation\Route;

final class RegisterAction
{
    public function __construct(
        protected readonly ResponseWrapper $responseWrapper,
    ) {
    }

    #[Route('/api/auth/register', name: 'api.auth.register', methods: 'POST', group: 'api')]
    public function __invoke(
        CredentialsRequest $request,
        Authenticator $authenticator,
        CommandBusInterface $commandBus
    ): ResponseInterface {
        try {
            $user = $commandBus->dispatch(new CreateUserCommand($request->getEmail(), $request->getPassword()));
            $token = $authenticator->authenticate($request->getCredentials(), 'header');

            return $this->responseWrapper->json([
                'data' => [
                    'token' => $token->getID(),
                    'user' => $user,
                ],
            ]);
        } catch (\Throwable $e) {
            return $this->responseWrapper->json(['errors' => [$e->getMessage()]], 422);
        }
    }
}

