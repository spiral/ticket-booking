<?php

declare(strict_types=1);

namespace App\Application\Query;

use Spiral\Cqrs\Attribute\QueryHandler;
use Spiral\Shared\GRPC\RequestContext;
use Spiral\Shared\Services\Users\v1\DTO\GetRequest;
use Spiral\Shared\Services\Users\v1\DTO\User;
use Spiral\Shared\Services\Users\v1\UsersServiceInterface;

final class GetUserHandler
{
    public function __construct(
        private readonly UsersServiceInterface $client
    ) {
    }

    #[QueryHandler]
    public function __invoke(GetUserQuery $query): User
    {
        $context = new RequestContext();
        $request = new GetRequest();

        $request->setId($query->userId);

        $response = $this->client->Get($context, $request);

        return $response->getUser();
    }
}
