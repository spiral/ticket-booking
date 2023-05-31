<?php

declare(strict_types=1);

namespace Spiral\Shared\GRPC\Interceptors;

use Google\Rpc\ErrorInfo;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;
use Spiral\Exceptions\ExceptionHandlerInterface;
use Spiral\RoadRunner\GRPC\Exception\GRPCException;
use Spiral\RoadRunner\GRPC\Exception\GRPCExceptionInterface;

final class ExceptionHandlerInterceptor implements CoreInterceptorInterface
{
    public function __construct(
        private readonly ExceptionHandlerInterface $errorHandler,
    ) {
    }

    /**
     * Handle exceptions.
     */
    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        try {
            return $core->callAction($controller, $action, $parameters);
        } catch (\Throwable $e) {
            $this->errorHandler->report($e);

            if ($e instanceof GRPCExceptionInterface) {
                throw $e;
            }

            $details = [];
            $previous = $e->getPrevious();
            while ($previous !== null) {
                $details[] = new ErrorInfo([
                    'domain' => $controller,
                ]);

                $previous = $previous->getPrevious();
            }

            throw new GRPCException(
                message: $e->getMessage(),
                details: $details,
                previous: $e
            );
        }
    }
}
