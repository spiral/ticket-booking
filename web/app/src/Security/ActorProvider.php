<?php

declare(strict_types=1);

namespace App\Security;

use Spiral\Auth\ActorProviderInterface;
use Spiral\Auth\TokenInterface;
use Spiral\Shared\GRPC\RequestContext;
use Spiral\Shared\Services\Users\v1\DTO\GetRequest;
use Spiral\Shared\Services\Users\v1\UsersServiceInterface;

final class ActorProvider implements ActorProviderInterface
{
    public function __construct(
        private readonly UsersServiceInterface $usersService
    ) {
    }

    public function getActor(TokenInterface $token): ?object
    {
        $data = $token->getPayload();

        if (!isset($data['userID'])) {
            return null;
        }

        $response = $this->usersService->Get(
            new RequestContext(),
            new GetRequest([
                'id' => $data['userID'],
            ])
        );

        if ($response->getUser() === null) {
            return null;
        }

        $user = new \stdClass();
        $user->id = $response->getUser()->getId();
        $user->email = $response->getUser()->getEmail();
        $user->roles = $response->getUser()->getRoles();

        return $user;
    }
}
