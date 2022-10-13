<?php

declare(strict_types=1);

namespace App\Bootloader;

use App\Repository\Postgres\UserRepository;
use App\Repository\UserRepositoryInterface;
use Spiral\Boot\Bootloader\Bootloader;

final class RepositoriesBootloader extends Bootloader
{
    protected const BINDINGS = [
        UserRepositoryInterface::class => UserRepository::class,
    ];
}
