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
use Spiral\Cqrs\QueryBusInterface;
use Spiral\Cqrs\QueryInterface;
use Spiral\Http\ResponseWrapper;
use Spiral\Views\ViewsInterface;

class AbstractController
{
    public function __construct(
        protected readonly ResponseWrapper $responseWrapper,
        protected readonly ViewsInterface $views,
        protected readonly QueryBusInterface $queryBus
    ) {
    }

    public function render(string $template, array $data = []): ResponseInterface
    {
        return $this->responseWrapper->html($this->views->render($template, $data));
    }

    public function ask(QueryInterface $query): mixed
    {
        return $this->queryBus->ask($query);
    }
}
