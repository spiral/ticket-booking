<?php

declare(strict_types=1);

namespace App\Bootloader;

use App\UI\Web\Middleware\LocaleSelector;
use App\UI\Web\Middleware\LoginMiddleware;
use Spiral\Auth\Middleware\AuthTransportMiddleware;
use Spiral\Bootloader\Http\RoutesBootloader as BaseRoutesBootloader;
use Spiral\Cookies\Middleware\CookiesMiddleware;
use Spiral\Core\Container\Autowire;
use Spiral\Csrf\Middleware\CsrfMiddleware;
use Spiral\Debug\StateCollector\HttpCollector;
use Spiral\Http\Middleware\ErrorHandlerMiddleware;
use Spiral\Http\Middleware\JsonPayloadMiddleware;
use Spiral\Session\Middleware\SessionMiddleware;

final class RoutesBootloader extends BaseRoutesBootloader
{
    protected function globalMiddleware(): array
    {
        return [
            ErrorHandlerMiddleware::class,
            JsonPayloadMiddleware::class,
            HttpCollector::class,
        ];
    }

    protected function middlewareGroups(): array
    {
        return [
            'web' => [
                CookiesMiddleware::class,
                SessionMiddleware::class,
                CsrfMiddleware::class,
                new Autowire(AuthTransportMiddleware::class, ['transportName' => 'cookie']),
            ],
            'personal' => [
                'middleware:web',
                LoginMiddleware::class,
            ],
            'api' => [
                new Autowire(AuthTransportMiddleware::class, ['transportName' => 'header'])
            ],
            'api_personal' => [
                'middleware:api',
                LoginMiddleware::class,
            ],
        ];
    }
}
