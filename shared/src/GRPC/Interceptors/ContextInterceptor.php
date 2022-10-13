<?php

declare(strict_types=1);

namespace Spiral\Shared\GRPC\Interceptors;

use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;
use Spiral\RoadRunner\GRPC\ContextInterface;
use Spiral\RoadRunner\GRPC\ServiceInterface;
use Spiral\Shared\GRPC\RequestContext;

final class ContextInterceptor implements CoreInterceptorInterface
{
    /**
     * Convert internal context to Request context.
     *
     * @param array{service: ServiceInterface, ctx: ContextInterface, input: string} $parameters
     */
    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        $parameters['ctx'] = new RequestContext($parameters['ctx']->getValues());

        return $core->callAction($controller, $action, $parameters);
    }
}
