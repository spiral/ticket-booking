<?php

declare(strict_types=1);

namespace App\Bootloader;

use App\Auth\TokenStorage;
use App\UI\Web\Interceptor\ValidationInterceptor;
use Spiral\Auth\TokenStorageInterface;
use Spiral\Bootloader\DomainBootloader;
use Spiral\Core\CoreInterface;
use Spiral\Domain\GuardInterceptor;

class AppBootloader extends DomainBootloader
{
    protected const SINGLETONS = [
        CoreInterface::class => [self::class, 'domainCore'],
        TokenStorageInterface::class => TokenStorage::class
    ];

    protected const INTERCEPTORS = [
        ValidationInterceptor::class,
        GuardInterceptor::class,
    ];
}
