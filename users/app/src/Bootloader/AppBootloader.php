<?php

declare(strict_types=1);

namespace App\Bootloader;

use App\TelemetryLoggerFactory;
use App\TokenStorage;
use App\UI\Web\Interceptor\ValidationInterceptor;
use Cycle\Database\LoggerFactoryInterface;
use Spiral\Auth\TokenStorageInterface;
use Spiral\Bootloader\DomainBootloader;
use Spiral\Core\CoreInterface;
use Spiral\Cycle\Interceptor\CycleInterceptor;
use Spiral\Domain\GuardInterceptor;

class AppBootloader extends DomainBootloader
{
    protected const SINGLETONS = [
        LoggerFactoryInterface::class => TelemetryLoggerFactory::class,
    ];
}
