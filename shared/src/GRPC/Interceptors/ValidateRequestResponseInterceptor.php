<?php

declare(strict_types=1);

namespace Spiral\Shared\GRPC\Interceptors;

use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;
use Spiral\RoadRunner\GRPC\Exception\GRPCException;
use Spiral\RoadRunner\GRPC\StatusCode;

class ValidateRequestResponseInterceptor implements CoreInterceptorInterface
{
    /**
     * Validate response status.
     */
    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        [$response, $status] = $core->callAction($controller, $action, $parameters);

        $code = $status->code ?? StatusCode::UNKNOWN;

        if (!$status || $code !== StatusCode::OK) {
            throw new GRPCException(
                message: $status->details ?? '',
                code: $code
            );
        }

        return [$response, $status];
    }
}
