<?php

declare(strict_types=1);

namespace App;

use App\Bootloader;
use Spiral\Boot\Bootloader\CoreBootloader;
use Spiral\Bootloader as Framework;
use Spiral\Cqrs\Bootloader\CqrsBootloader;
use Spiral\Debug\Bootloader\DumperBootloader;
use Spiral\DotEnv\Bootloader as DotEnv;
use Spiral\Events\Bootloader\EventsBootloader;
use Spiral\Framework\Kernel;
use Spiral\SendIt\Bootloader\MailerBootloader;
use Spiral\Shared\Bootloader\SharedBootloader;
use Spiral\RoadRunnerBridge\Bootloader as RoadRunnerBridge;
use Spiral\Tokenizer\Bootloader\TokenizerBootloader;
use Spiral\Validation\Bootloader\ValidationBootloader;

class App extends Kernel
{
    protected const SYSTEM = [
        CoreBootloader::class,
        DumperBootloader::class,
        TokenizerBootloader::class,
        DotEnv\DotenvBootloader::class,
    ];

    /*
     * List of components and extensions to be automatically registered
     * within system container on application start.
     */
    protected const LOAD = [
        \Spiral\OpenTelemetry\Bootloader\OpenTelemetryBootloader::class,

        EventsBootloader::class,

        // Logging and exceptions handling
        //Monolog\MonologBootloader::class,
        //LoggerBootloader::class,
        Bootloader\ExceptionHandlerBootloader::class,

        // RoadRunner
        RoadRunnerBridge\RoadRunnerBootloader::class,
        RoadRunnerBridge\CentrifugoBootloader::class,

        // Core Services
        Framework\SnapshotsBootloader::class,

        // Security and validation
        Framework\Security\EncrypterBootloader::class,
        ValidationBootloader::class,
        Framework\Security\FiltersBootloader::class,
        Framework\Security\GuardBootloader::class,

        // Application specific logs
        Bootloader\LoggingBootloader::class,

        // Framework commands
        Framework\CommandBootloader::class,

        // Debug and debug extensions
        Framework\DebugBootloader::class,
        Framework\Debug\LogCollectorBootloader::class,
        Framework\Debug\HttpCollectorBootloader::class,
        MailerBootloader::class,

        CqrsBootloader::class,
        SharedBootloader::class,
    ];

    protected const APP = [
        Bootloader\AppBootloader::class
    ];
}
