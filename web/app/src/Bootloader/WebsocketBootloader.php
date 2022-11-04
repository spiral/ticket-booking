<?php

declare(strict_types=1);

namespace App\Bootloader;

use App\Websocket\JwtTokenGenerator;
use App\Websocket\TokenGeneratorInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\EnvironmentInterface;

final class WebsocketBootloader extends Bootloader
{
    protected const SINGLETONS = [
        TokenGeneratorInterface::class => [self::class, 'initGenerator']
    ];

    private function initGenerator(
        EnvironmentInterface $env
    ): TokenGeneratorInterface
    {
        return new JwtTokenGenerator(
            $env->get('CENTRIFUGO_SECRET', 'secret')
        );
    }
}
