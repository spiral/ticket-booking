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

    public function authenticate(Credentials $credentials, string $transport = 'cookie'): ?Token
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
            // todo throw an Exception
            return null;
        }

        $token = new Token(
            $t->getId(),
            \json_decode($t->getPayload(), true),
            $t->getExpiresAt()->toDateTime()
        );

        $this->authScope->start(
            token: $token,
            transport: $transport
        );

        return $token;
    }

    public function close(string $token): void
    {
        if ($this->authScope->getToken() === null || $this->authScope->getToken()->getID() !== $token) {
            return;
        }

        $this->authScope->close();
    }
}
