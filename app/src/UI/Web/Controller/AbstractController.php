<?php

/**
 * This file is part of Spiral package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\UI\Web\Controller;

use Psr\Http\Message\ResponseInterface;
use Spiral\Auth\AuthScope;
use Spiral\Cqrs\CommandBusInterface;
use Spiral\Cqrs\CommandInterface;
use Spiral\Cqrs\QueryBusInterface;
use Spiral\Cqrs\QueryInterface;
use Spiral\Http\ResponseWrapper;
use Spiral\Router\RouterInterface;
use Spiral\Translator\TranslatorInterface;
use Spiral\Views\ViewsInterface;

class AbstractController
{
    public function __construct(
        protected readonly ResponseWrapper $responseWrapper,
        protected readonly ViewsInterface $views,
        protected readonly CommandBusInterface $commandBus,
        protected readonly QueryBusInterface $queryBus,
        protected readonly TranslatorInterface $translator,
        protected readonly RouterInterface $router,
        protected readonly AuthScope $authScope
    ) {
    }

    public function render(string $template, array $data = []): ResponseInterface
    {
        return $this->responseWrapper->html(
            $this->views->render($template, \array_merge($data, ['auth' => $this->authScope]))
        );
    }

    public function json(mixed $data, int $code = 200): ResponseInterface
    {
        return $this->responseWrapper->json($data, $code);
    }

    protected function redirectToRoute(string $route, array $parameters = [], int $status = 301): ResponseInterface
    {
        return $this->redirect((string) $this->router->uri($route, $parameters), $status);
    }

    protected function redirect(string $path, int $status = 301): ResponseInterface
    {
        return $this->responseWrapper->redirect($path, $status);
    }

    /** @throws \Throwable */
    public function exec(CommandInterface $command): void
    {
        $this->commandBus->dispatch($command);
    }

    public function ask(QueryInterface $query): mixed
    {
        return $this->queryBus->ask($query);
    }
}
