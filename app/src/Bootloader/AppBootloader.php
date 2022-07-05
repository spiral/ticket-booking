<?php

declare(strict_types=1);

namespace App\Bootloader;

use App\UI\Web\Interceptor\ValidationInterceptor;
use Spiral\Bootloader\DomainBootloader;
use Spiral\Core\CoreInterface;
use Spiral\Cycle\Interceptor\CycleInterceptor;

class AppBootloader extends DomainBootloader
{
    protected const SINGLETONS = [
        CoreInterface::class => [self::class, 'domainCore']
    ];

    protected const INTERCEPTORS = [
        CycleInterceptor::class,
        ValidationInterceptor::class,
    ];
}
