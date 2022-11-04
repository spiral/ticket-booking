<?php

declare(strict_types=1);

namespace App\Bootloader;

use Spiral\Auth\TokenStorageInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Shared\Auth\TokenStorage;

final class AppBootloader extends Bootloader
{
    protected const SINGLETONS = [
        TokenStorageInterface::class => TokenStorage::class,
    ];
}
