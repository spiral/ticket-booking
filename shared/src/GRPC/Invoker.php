<?php

declare(strict_types=1);

namespace Spiral\Shared\GRPC;

use Spiral\Core\Container;
use Spiral\Core\InterceptableCore;
use Spiral\Shared\GRPC\Interceptors\InjectTelemetryFromContextInterceptor;
use Spiral\RoadRunner\GRPC\ContextInterface;
use Spiral\RoadRunner\GRPC\InvokerInterface;
use Spiral\RoadRunner\GRPC\Method;
use Spiral\RoadRunner\GRPC\ServiceInterface;
use Spiral\Shared\GRPC\Interceptors\ContextInterceptor;
use Spiral\Shared\GRPC\Interceptors\ExceptionHandlerInterceptor;
use Spiral\Shared\GRPC\Interceptors\GuardInterceptor;
use Spiral\Shared\GRPC\Interceptors\InjectableInterceptor;

final class Invoker implements InvokerInterface
{
    public function __construct(
        private readonly Container $container,
        private readonly InterceptableCore $core
    ) {
        $this->core->addInterceptor($this->container->get(ExceptionHandlerInterceptor::class));
        $this->core->addInterceptor($this->container->get(ContextInterceptor::class));
        $this->core->addInterceptor($this->container->get(InjectTelemetryFromContextInterceptor::class));
        $this->core->addInterceptor($this->container->get(GuardInterceptor::class));
        $this->core->addInterceptor(
            $this->container->make(InjectableInterceptor::class, [
                'core' => $this->core,
            ])
        );
    }

    public function invoke(ServiceInterface $service, Method $method, ContextInterface $ctx, ?string $input): string
    {
        return $this->core->callAction($service::class, $method->getName(), [
            'service' => $service,
            'ctx' => $ctx,
            'method' => $method,
            'input' => $input,
        ]);
    }
}
