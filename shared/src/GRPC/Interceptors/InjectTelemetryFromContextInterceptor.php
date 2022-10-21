<?php

declare(strict_types=1);

namespace Spiral\Shared\GRPC\Interceptors;

use Spiral\Core\CoreInterceptorInterface;
use Spiral\Shared\GRPC\RequestContext;
use Spiral\Telemetry\TraceKind;
use Spiral\Telemetry\TracerFactoryInterface;
use Spiral\Core\CoreInterface;

class InjectTelemetryFromContextInterceptor implements CoreInterceptorInterface
{
    public function __construct(
        private readonly TracerFactoryInterface $tracerFactory
    ) {
    }

    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        $ctx = [];

        if (isset($parameters['ctx']) and $parameters['ctx'] instanceof RequestContext) {
            $ctx = $parameters['ctx']->getTelemetry();
        }

        return $this->tracerFactory->make($ctx)->trace(
            name: \sprintf('Interceptor [%s]', __CLASS__),
            callback: static fn(): mixed => $core->callAction($controller, $action, $parameters),
            attributes: [
                'controller' => $controller,
                'action' => $action,
            ],
            scoped: true,
            traceKind: TraceKind::SERVER
        );
    }
}
