<?php

declare(strict_types=1);

namespace App\UI\Web\Controller\Api\Auth;

use App\Security\Authenticator;
use App\UI\Web\Request\CredentialsRequest;
use Psr\Http\Message\ResponseInterface;
use Spiral\Filters\Exception\ValidationException;
use Spiral\Http\ResponseWrapper;
use Spiral\Router\Annotation\Route;

final class LoginAction
{
    public function __construct(
        protected readonly ResponseWrapper $responseWrapper,
    ) {
    }

    #[Route('/api/auth/login', name: 'api.auth.login', methods: 'POST', group: 'api')]
    public function __invoke(CredentialsRequest $request, Authenticator $authenticator): ResponseInterface
    {
        try {
            $token = $authenticator->authenticate($request->getCredentials(), 'header');

            if (!$token) {
                return $this->responseWrapper->json(
                    ['errors' => ['Username or password is not correct.']],
                    422
                );
            }

            return $this->responseWrapper->json(['data' => [
                'token' => $token->getID()
            ]]);
        } catch (\Throwable $e) {
            return $this->responseWrapper->json(['errors' => [
                'email' => $e->getMessage()
            ]], 422);
        }
    }
}
