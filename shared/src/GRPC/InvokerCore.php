<?php

declare(strict_types=1);

namespace Spiral\Shared\GRPC;

use Spiral\Core\CoreInterface;
use Spiral\RoadRunner\GRPC\ContextInterface;
use Spiral\RoadRunner\GRPC\InvokerInterface;
use Spiral\RoadRunner\GRPC\Method;
use Spiral\RoadRunner\GRPC\ServiceInterface;
use Spiral\Telemetry\TracerInterface;

class InvokerCore implements CoreInterface
{
    public function __construct(
        private readonly InvokerInterface $invoker,
        private readonly TracerInterface $tracer
    ) {
    }

    /**
     * @param array{service: ServiceInterface, method: Method, ctx: ContextInterface, input: ?string} $parameters
     */
    public function callAction(string $controller, string $action, array $parameters = []): mixed
    {
        return $this->tracer->trace(
            name: \sprintf('GRPC Service %s:%s', $controller, $action),
            callback: fn() => $this->invoker->invoke(
                $parameters['service'],
                $parameters['method'],
                $parameters['ctx'],
                $parameters['input'],
            ),
            attributes: \array_keys($parameters)
        );
    }
}
