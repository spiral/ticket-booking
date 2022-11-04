<?php

declare(strict_types=1);

namespace App\Security;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Spiral\Auth\HttpTransportInterface;
use Spiral\Http\Request\InputBag;

class CentrifugoTokenTransport implements HttpTransportInterface
{
    public function __construct(
        private readonly string $key = 'data.authToken',
        private readonly string $valueFormat = '%s'
    ) {
    }

    private function getData(Request $request): InputBag
    {
        return new InputBag($request->getParsedBody());
    }

    public function fetchToken(Request $request): ?string
    {
        return (string) $this->getData($request)->get($this->key);
    }

    public function commitToken(
        Request $request,
        Response $response,
        string $tokenID,
        \DateTimeInterface $expiresAt = null
    ): Response {
        return $response;
    }

    public function removeToken(Request $request, Response $response, string $tokenID): Response
    {
        return $response;
    }
}
