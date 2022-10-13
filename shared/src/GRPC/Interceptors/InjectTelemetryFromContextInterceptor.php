<?php

declare(strict_types=1);

namespace Spiral\Shared\GRPC\Interceptors;

use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\ScopeInterface;
use Spiral\Shared\GRPC\RequestContext;
use Spiral\Telemetry\TraceKind;
use Spiral\Telemetry\TracerFactoryInterface;
use Spiral\Core\CoreInterface;
use Spiral\Telemetry\TracerInterface;

class InjectTelemetryFromContextInterceptor implements CoreInterceptorInterface
{
    public function __construct(
        private readonly TracerFactoryInterface $tracerFactory,
        private readonly ScopeInterface $scope
    ) {
    }

    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        $ctx = [];
        if (isset($parameters['ctx']) and $parameters['ctx'] instanceof RequestContext) {
            $ctx = $parameters['ctx']->getTelemetry();
        }

        $tracer = $this->tracerFactory->fromContext($ctx);

        return $this->scope->runScope([
            TracerInterface::class => $tracer,
        ], fn(): mixed => $tracer->trace(
            name: \sprintf('Interceptor [%s]', __CLASS__),
            callback: fn(): mixed => $core->callAction($controller, $action, $parameters),
            scoped: true,
            attributes: [
                'controller' => $controller,
                'action' => $action,
            ],
            traceKind: TraceKind::SERVER
        ));
    }
}
