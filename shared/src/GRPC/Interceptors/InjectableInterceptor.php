<?php

declare(strict_types=1);

namespace Spiral\Shared\GRPC\Interceptors;

use ReflectionClass;
use Spiral\Attributes\ReaderInterface;
use Spiral\Core\Container;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;
use Spiral\Core\InterceptableCore;
use Spiral\Shared\Attributes\InjectInterceptor;

final class InjectableInterceptor implements CoreInterceptorInterface
{
    public function __construct(
        private readonly Container $container,
        private readonly ReaderInterface $reader,
        private readonly InterceptableCore $core
    ) {
    }

    /**
     * Inject service method interceptors into core.
     */
    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        $refl = new ReflectionClass($controller);

        /** @var InjectInterceptor[] $injectors */
        $injectors = $this->reader->getFunctionMetadata($refl->getMethod($action), InjectInterceptor::class);

        foreach ($injectors as $injector) {
            $this->core->addInterceptor($this->container->get($injector->getClass()));
        }

        return $core->callAction($controller, $action, $parameters);
    }
}
