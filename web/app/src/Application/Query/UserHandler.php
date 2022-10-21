<?php

declare(strict_types=1);

namespace App\Application\Query;

use Spiral\Cqrs\Attribute\QueryHandler;
use Spiral\Shared\GRPC\RequestContext;
use Spiral\Shared\Services\Users\v1\DTO\GetRequest;
use Spiral\Shared\Services\Users\v1\UsersServiceInterface;

final class UserHandler
{
    public function __construct(
        private readonly UsersServiceInterface $usersService,
    ) {
    }

    #[QueryHandler]
    public function __invoke(UserQuery $query): iterable
    {
        $response = $this->usersService->Get(
            new RequestContext(),
            new GetRequest([
                'id' => $query->userId,
            ])
        );

        return [
            'id' => $response->getUser()->getId(),
            'email' => $response->getUser()->getEmail(),
            'roles' => $response->getUser()->getRoles()
        ];
    }
}
