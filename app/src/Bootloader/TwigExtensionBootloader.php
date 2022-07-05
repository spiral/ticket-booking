<?php

declare(strict_types=1);

namespace App\Bootloader;

use App\UI\Web\Twig\Extension\RouteExtension;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Bootloader\Views\TranslatedCacheBootloader;
use Spiral\Twig\Bootloader\TwigBootloader;

class TwigExtensionBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        TranslatedCacheBootloader::class,
        TwigBootloader::class
    ];

    public function boot(TwigBootloader $twig)
    {
        $twig->addExtension(RouteExtension::class);
    }
}
