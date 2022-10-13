<?php

declare(strict_types=1);

namespace App;

use App\Bootloader;
use Spiral\Boot\Bootloader\CoreBootloader;
use Spiral\Bootloader as Framework;
use Spiral\DotEnv\Bootloader as DotEnv;
use Spiral\EventBus\Bootloader\EventBusBootloader;
use Spiral\Events\Bootloader\EventsBootloader;
use Spiral\Framework\Kernel;
use Spiral\Monolog\Bootloader as Monolog;
use Spiral\Shared\Bootloader\SharedBootloader;
use Spiral\Prototype\Bootloader as Prototype;
use Spiral\Cycle\Bootloader as CycleBridge;
use Spiral\RoadRunnerBridge\Bootloader as RoadRunnerBridge;
use Spiral\TemporalBridge\Bootloader\TemporalBridgeBootloader;
use Spiral\Tokenizer\Bootloader\TokenizerBootloader;
use Spiral\Validation\Bootloader\ValidationBootloader;

class App extends Kernel
{
    protected const SYSTEM = [
        CoreBootloader::class,
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
        Monolog\MonologBootloader::class,
        Bootloader\ExceptionHandlerBootloader::class,

        // RoadRunner
        RoadRunnerBridge\GRPCBootloader::class,
        RoadRunnerBridge\QueueBootloader::class,
        RoadRunnerBridge\RoadRunnerBootloader::class,
        RoadRunnerBridge\BroadcastingBootloader::class,
        TemporalBridgeBootloader::class,

        EventsBootloader::class,
        EventBusBootloader::class,

        // Core Services
        Framework\SnapshotsBootloader::class,

        // Security and validation
        Framework\Security\EncrypterBootloader::class,
        ValidationBootloader::class,
        Framework\Security\FiltersBootloader::class,
        Framework\Security\GuardBootloader::class,

        // Databases
        CycleBridge\DatabaseBootloader::class,
        CycleBridge\MigrationsBootloader::class,
        // CycleBridge\DisconnectsBootloader::class,

        // Application specific logs
        Bootloader\LoggingBootloader::class,

        // ORM
        CycleBridge\SchemaBootloader::class,
        CycleBridge\CycleOrmBootloader::class,
        CycleBridge\AnnotatedBootloader::class,
        CycleBridge\CommandBootloader::class,

        // DataGrid
        // CycleBridge\DataGridBootloader::class,

        // Auth
        CycleBridge\AuthTokensBootloader::class,

        // Entity checker
        // CycleBridge\ValidationBootloader::class,

        // Framework commands
        Framework\CommandBootloader::class,

        // Debug and debug extensions
        Framework\DebugBootloader::class,
        Framework\Debug\LogCollectorBootloader::class,
        Framework\Debug\HttpCollectorBootloader::class,

        RoadRunnerBridge\CommandBootloader::class,
        \Spiral\DatabaseSeeder\Bootloader\DatabaseSeederBootloader::class,
    ];

    /*
     * Application specific services and extensions.
     */
    protected const APP = [
        SharedBootloader::class,
    ];
}
