<?php

declare(strict_types=1);

namespace App\Security;

use App\ValueObject\Credentials;
use Spiral\Auth\AuthScope;
use Spiral\Auth\Session\Token;
use Spiral\Shared\GRPC\RequestContext;
use Spiral\Shared\Services\Users\v1\DTO\AuthRequest;
use Spiral\Shared\Services\Users\v1\UsersServiceInterface;

final class Authenticator
{
    public function __construct(
        private readonly UsersServiceInterface $usersService,
        private readonly AuthScope $authScope
    ) {
    }

    public function authenticate(Credentials $credentials): void
    {
        $response = $this->usersService->Auth(
            new RequestContext(),
            new AuthRequest([
                'email' => $credentials->email->getValue(),
                'password' => $credentials->plainPassword,
            ])
        );

        $t = $response->getToken();
        if (!$t) {
            return;
        }

        $token = new Token(
            $response->getToken()->getId(),
            \json_decode($response->getToken()->getPayload(), true),
            $response->getToken()->getExpiresAt()->toDateTime()
        );

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
