<?php

declare(strict_types=1);

namespace Spiral\Shared\GRPC\Interceptors;

use Spiral\Attributes\ReaderInterface;
use Spiral\Auth\Exception\TokenStorageException;
use Spiral\Auth\TokenInterface;
use Spiral\Auth\TokenStorageInterface;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;
use Spiral\RoadRunner\GRPC\ContextInterface;
use Spiral\RoadRunner\GRPC\Exception\UnauthenticatedException;
use Spiral\RoadRunner\GRPC\ServiceInterface;
use Spiral\Shared\Attributes\Guarded;
use Spiral\Shared\GRPC\RequestContext;

final class GuardInterceptor implements CoreInterceptorInterface
{
    public function __construct(
        private readonly ReaderInterface $reader,
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    /**
     * Check auth token for service methods with Guarded attribute.
     *
     * @param array{service: ServiceInterface, ctx: ContextInterface, input: string} $parameters
     */
    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        $refl = new \ReflectionClass($controller);
        /** @var Guarded|null $guard */
        $guard = $this->reader->firstFunctionMetadata($refl->getMethod($action), Guarded::class);

        if ($guard) {
            $token = $this->checkGuard($guard, $parameters['ctx']);
            $parameters['ctx'] = $parameters['ctx']->withValue(TokenInterface::class, $token);
        }

        return $core->callAction($controller, $action, $parameters);
    }

    private function checkGuard(?Guarded $guard, RequestContext $ctx): TokenInterface
    {
        $token = $ctx->getValue($guard->getTokenField())[0] ?? null;
        if (!$token) {
            throw new UnauthenticatedException('Token is missing');
        }

        try {
            return $this->tokenStorage->load($token);
        } catch (TokenStorageException $e) {
            throw new UnauthenticatedException(
                message: $e->getMessage(),
                previous: $e
            );
        }
    }
}
