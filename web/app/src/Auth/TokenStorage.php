<?php

declare(strict_types=1);

namespace App\Auth;

use Carbon\Carbon;
use Google\Protobuf\Timestamp;
use Spiral\Auth\Session\Token;
use Spiral\Auth\TokenInterface;
use Spiral\Auth\TokenStorageInterface;
use Spiral\Shared\GRPC\RequestContext;
use Spiral\Shared\Services\Tokens\v1\DTO\CreateRequest;
use Spiral\Shared\Services\Tokens\v1\DTO\DeleteRequest;
use Spiral\Shared\Services\Tokens\v1\DTO\LoadRequest;
use Spiral\Shared\Services\Tokens\v1\TokensServiceInterface;

class TokenStorage implements TokenStorageInterface
{
    public function __construct(
        private readonly TokensServiceInterface $tokensService
    ) {
    }

    public function load(string $id): ?TokenInterface
    {
        $response = $this->tokensService->Load(
            new RequestContext(),
            new LoadRequest([
                'id' => $id,
            ])
        );

        if (!$response->getToken()) {
            return null;
        }

        return $this->convertDTOToToken($response->getToken());
    }

    public function create(array $payload, \DateTimeInterface $expiresAt = null): TokenInterface
    {
        $request = new CreateRequest([
            'payload' => \json_encode($payload),
        ]);

        if ($expiresAt) {
            $date = new Timestamp();
            $date->fromDateTime(Carbon::instance($expiresAt)->toDateTime());
            $request->setExpiresAt($date);
        }

        $response = $this->tokensService->Create(
            new RequestContext(),
            $request
        );

        return $this->convertDTOToToken($response->getToken());
    }

    public function delete(TokenInterface $token): void
    {
        $this->tokensService->Delete(
            new RequestContext(),
            new DeleteRequest([
                'id' => $token->getID(),
            ])
        );
    }

    private function convertDTOToToken(\Spiral\Shared\Services\Tokens\v1\DTO\Token $token): Token
    {
        return new Token(
            $token->getId(),
            \json_decode($token->getPayload(), true),
            $token->getExpiresAt()->toDateTime()
        );
    }
}
