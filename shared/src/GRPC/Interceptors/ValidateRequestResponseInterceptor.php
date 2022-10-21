<?php

declare(strict_types=1);

namespace Spiral\Shared\GRPC\Interceptors;

use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;
use Spiral\RoadRunner\GRPC\StatusCode;
use Spiral\Shared\Exception\ResponseException;

class ValidateRequestResponseInterceptor implements CoreInterceptorInterface
{
    /**
     * Validate response status.
     */
    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        [$response, $status] = $core->callAction($controller, $action, $parameters);

        $code = $status->code ?? StatusCode::UNKNOWN;

        if ($code !== StatusCode::OK) {
            throw ResponseException::createFromStatus($status);
        }

        return [$response, $status];
    }
}
