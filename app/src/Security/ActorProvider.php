<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use Spiral\Auth\ActorProviderInterface;
use Spiral\Auth\TokenInterface;

final class ActorProvider implements ActorProviderInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function getActor(TokenInterface $token): ?User
    {
        $data = $token->getPayload();

        if (! isset($data['userID'])) {
            return null;
        }

        return $this->userRepository->findByPK($data['userID']);
    }
}
