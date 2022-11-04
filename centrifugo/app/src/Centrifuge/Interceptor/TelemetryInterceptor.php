<?php

declare(strict_types=1);

namespace App\Centrifuge\Interceptor;

use RoadRunner\Centrifugo\RequestInterface;
use RoadRunner\Centrifugo\RPCRequest;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;
use Spiral\Telemetry\TracerInterface;

final class TelemetryInterceptor implements CoreInterceptorInterface
{
    public function __construct(
        private readonly TracerInterface $tracer
    ) {
    }

    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        \assert($parameters['request'] instanceof RequestInterface);

        return $this->tracer->trace('Centrifugo request', function () use ($core, $controller, $action, $parameters) {
            return $core->callAction($controller, $action, $parameters);
        }, attributes: [
            'centrifugo.class' => $parameters['request']::class,
            'centrifugo.client' => $parameters['request']->client,
            'centrifugo.headers' => $parameters['request']->headers,
        ], scoped: true);
    }
}
