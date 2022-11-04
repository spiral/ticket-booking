<?php

declare(strict_types=1);

namespace App\Bootloader;

use App\TelemetryLoggerFactory;
use App\UI\Web\Interceptor\ValidationInterceptor;
use Cycle\Database\LoggerFactoryInterface;
use Spiral\Auth\TokenStorageInterface;
use Spiral\Bootloader\DomainBootloader;
use Spiral\Core\CoreInterface;
use Spiral\Cycle\Interceptor\CycleInterceptor;
use Spiral\Domain\GuardInterceptor;
use Spiral\Shared\Auth\TokenStorage;

class AppBootloader extends DomainBootloader
{
    protected const SINGLETONS = [
        CoreInterface::class => [self::class, 'domainCore'],
        LoggerFactoryInterface::class => TelemetryLoggerFactory::class,
        TokenStorageInterface::class => TokenStorage::class
    ];

    protected const INTERCEPTORS = [
        CycleInterceptor::class,
        ValidationInterceptor::class,
        GuardInterceptor::class,
    ];
}
