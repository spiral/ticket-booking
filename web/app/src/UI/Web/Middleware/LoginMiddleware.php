<?php

declare(strict_types=1);

namespace App\UI\Web\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spiral\Auth\Middleware\Firewall\AbstractFirewall;
use Spiral\Http\ResponseWrapper;

final class LoginMiddleware extends AbstractFirewall
{
    public function __construct(
        private readonly UriInterface $uri,
        private readonly ResponseWrapper $responseFactory
    ) {
    }

    public function denyAccess(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->responseFactory->redirect($this->uri, 302);
    }
}
