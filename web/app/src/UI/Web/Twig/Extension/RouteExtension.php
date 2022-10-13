<?php

declare(strict_types=1);

namespace App\UI\Web\Twig\Extension;

use Spiral\Router\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class RouteExtension extends AbstractExtension
{
    public function __construct(
        private readonly RouterInterface $router
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'route',
                fn (string $route, array $parameters = []) => (string) $this->router->uri($route, $parameters),
                ['needs_environment' => false]
            )
        ];
    }
}
