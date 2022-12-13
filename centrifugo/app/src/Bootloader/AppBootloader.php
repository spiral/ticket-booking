<?php

declare(strict_types=1);

namespace App\Bootloader;

use App\Auth\TokenStorage;
use Spiral\Auth\TokenStorageInterface;
use Spiral\Boot\Bootloader\Bootloader;

final class AppBootloader extends Bootloader
{
    protected const SINGLETONS = [
        TokenStorageInterface::class => TokenStorage::class,
    ];
}
