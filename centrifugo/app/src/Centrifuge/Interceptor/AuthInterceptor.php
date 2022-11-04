<?php

declare(strict_types=1);

namespace App\Centrifuge\Interceptor;

use RoadRunner\Centrifugo\RequestInterface;
use Spiral\Auth\TokenStorageInterface;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;

class AuthInterceptor implements CoreInterceptorInterface
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
    ) {
    }

    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        \assert($parameters['request'] instanceof RequestInterface);

        $authToken = $parameters['request']->getData()['authToken'] ?? null;
        if ($authToken && $token = $this->tokenStorage->load($authToken)) {
            $parameters['request'] = $parameters['request']->withAttribute(
                'user_id',
                $token->getPayload()['userID'] ?? null
            );
        }

        return $core->callAction($controller, $action, $parameters);
    }
}
