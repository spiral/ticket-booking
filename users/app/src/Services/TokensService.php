<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Mappers\TimestampFactory;
use Spiral\Auth\TokenInterface;
use Spiral\Auth\TokenStorageInterface;
use Spiral\RoadRunner\GRPC;
use Spiral\Shared\Services\Tokens\v1\TokensServiceInterface;
use Spiral\Shared\Services\Tokens\v1\DTO;

class TokensService implements TokensServiceInterface
{
    public function __construct(
        private readonly TokenStorageInterface $storage
    ) {
    }

    public function Create(
        GRPC\ContextInterface $ctx,
        DTO\CreateRequest $in
    ): DTO\CreateResponse {
        $expiresAt = $in->getExpiresAt();

        $token = $this->storage->create(
            \json_decode($in->getPayload(), true),
            $expiresAt ? $expiresAt->toDateTime() : null
        );

        return new DTO\CreateResponse([
            'token' => $this->convertToDTO($token),
        ]);
    }

    public function Load(
        GRPC\ContextInterface $ctx,
        DTO\LoadRequest $in
    ): DTO\LoadResponse {
        $token = $this->storage->load($in->getId());

        if (!$token) {
            return new DTO\LoadResponse();
        }

        return new DTO\LoadResponse([
            'token' => $this->convertToDTO($token),
        ]);
    }

    public function Delete(
        GRPC\ContextInterface $ctx,
        DTO\DeleteRequest $in
    ): DTO\DeleteResponse {
        $token = $this->storage->load($in->getId());

        if ($token) {
            $this->storage->delete($token);
        }

        return new DTO\DeleteResponse();
    }

    private function convertToDTO(TokenInterface $token): DTO\Token
    {
        $dto = new DTO\Token([
            'id' => $token->getID(),
            'payload' => \json_encode($token->getPayload()),
        ]);

        if ($token->getExpiresAt()) {
            $dto->setExpiresAt(TimestampFactory::fromDateTimeInterface($token->getExpiresAt()));
        }

        return $dto;
    }
}
