<?php

declare(strict_types=1);

namespace Spiral\Shared\Bootloader;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\SocketHandler;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\EnvironmentInterface;
use Spiral\Monolog\Bootloader\MonologBootloader;

final class LoggerBootloader extends Bootloader
{
    public function boot(MonologBootloader $bootloader, EnvironmentInterface $env)
    {
        $handler = new SocketHandler($env->get('MONOLOG_SOCKET_HOST'));
        $handler->setFormatter(new JsonFormatter());

        $bootloader->addHandler('socket', $handler);
    }
}
