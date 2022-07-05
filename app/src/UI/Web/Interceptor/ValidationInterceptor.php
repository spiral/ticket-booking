<?php

declare(strict_types=1);

namespace App\UI\Web\Interceptor;

use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;
use Spiral\Filters\Exception\ValidationException;
use Spiral\Http\Request\InputManager;
use Spiral\Http\ResponseWrapper;

final class ValidationInterceptor implements CoreInterceptorInterface
{
    public function __construct(
        private readonly InputManager $input,
        private readonly ResponseWrapper $responseWrapper
    ) {
    }

    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        if ($this->input->isJsonExpected() || $this->input->isXmlHttpRequest()) {
            try {
                return $core->callAction($controller, $action, $parameters);
            } catch (ValidationException $e) {
                return $this->responseWrapper->json(['errors' => $e->errors, 400]);
            }
        }

        return $core->callAction($controller, $action, $parameters);
    }
}
