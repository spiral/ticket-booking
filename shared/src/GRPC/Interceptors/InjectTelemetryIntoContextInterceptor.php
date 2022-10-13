<?php

declare(strict_types=1);

namespace Spiral\Shared\GRPC\Interceptors;

use Psr\Container\ContainerInterface;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Shared\GRPC\RequestContext;
use Spiral\RoadRunner\GRPC\ContextInterface;
use Spiral\RoadRunner\GRPC\ResponseHeaders;
use Spiral\Telemetry\TraceKind;
use Spiral\Telemetry\TracerInterface;
use Spiral\Core\CoreInterface;

class InjectTelemetryIntoContextInterceptor implements CoreInterceptorInterface
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        $tracer = $this->container->get(TracerInterface::class);
        \assert($tracer instanceof TracerInterface);

        if (isset($parameters['ctx']) and $parameters['ctx'] instanceof RequestContext) {
            $parameters['ctx'] = $parameters['ctx']->withTelemetry($tracer->getContext());
        }

        return $tracer->trace(
            name: __CLASS__,
            callback: fn() => $core->callAction($controller, $action, $parameters),
            attributes: compact('controller', 'action'),
            traceKind: TraceKind::PRODUCER
        );
    }
}
