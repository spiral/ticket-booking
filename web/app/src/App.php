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
use Spiral\Nyholm\Bootloader as Nyholm;
use Spiral\OpenTelemetry\Bootloader\OpenTelemetryBootloader;
use Spiral\Prototype\Bootloader as Prototype;
use Spiral\Router\Bootloader\AnnotatedRoutesBootloader;
use Spiral\Scaffolder\Bootloader as Scaffolder;
use Spiral\Cycle\Bootloader as CycleBridge;
use Spiral\RoadRunnerBridge\Bootloader as RoadRunnerBridge;
use Spiral\Serializer\Bootloader\SerializerBootloader;
use Spiral\Shared\Bootloader\SharedBootloader;
use Spiral\Stempler\Bootloader\StemplerBootloader;
use Spiral\Tokenizer\Bootloader\TokenizerBootloader;
use Spiral\Twig\Bootloader\TwigBootloader;
use Spiral\Validation\Symfony\Bootloader\ValidatorBootloader;
use Spiral\Views\Bootloader\ViewsBootloader;

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
        OpenTelemetryBootloader::class,

        // Logging and exceptions handling
        Monolog\MonologBootloader::class,
        Bootloader\ExceptionHandlerBootloader::class,

        // RoadRunner
        RoadRunnerBridge\CacheBootloader::class,
        RoadRunnerBridge\GRPCBootloader::class,
        RoadRunnerBridge\HttpBootloader::class,
        RoadRunnerBridge\QueueBootloader::class,
        RoadRunnerBridge\RoadRunnerBootloader::class,
        RoadRunnerBridge\BroadcastingBootloader::class,

        // Core Services
        Framework\SnapshotsBootloader::class,
        Framework\I18nBootloader::class,

        // Security and validation
        Framework\Security\EncrypterBootloader::class,
        Framework\Security\FiltersBootloader::class,
        Framework\Security\GuardBootloader::class,
        ValidatorBootloader::class,

        // HTTP extensions
        Nyholm\NyholmBootloader::class,
        Framework\Http\RouterBootloader::class,
        Framework\Http\JsonPayloadsBootloader::class,
        Framework\Http\CookiesBootloader::class,
        Framework\Http\SessionBootloader::class,
        Framework\Http\CsrfBootloader::class,
        Framework\Http\PaginationBootloader::class,
        AnnotatedRoutesBootloader::class,

        // Databases
        CycleBridge\DatabaseBootloader::class,
        CycleBridge\MigrationsBootloader::class,
        // CycleBridge\DisconnectsBootloader::class,

        // ORM
        CycleBridge\SchemaBootloader::class,
        CycleBridge\CycleOrmBootloader::class,
        CycleBridge\AnnotatedBootloader::class,
        CycleBridge\CommandBootloader::class,
        //CycleBridge\AuthTokensBootloader::class,

        // DataGrid
        CycleBridge\DataGridBootloader::class,

        // Auth
        Framework\Auth\HttpAuthBootloader::class,
        //Framework\Auth\TokenStorage\SessionTokensBootloader::class,
        Framework\Auth\SecurityActorBootloader::class,

        // Entity checker
        // CycleBridge\ValidationBootloader::class,

        // Views and view translation
        ViewsBootloader::class,
        Framework\Views\TranslatedCacheBootloader::class,

        // Extensions and bridges
        TwigBootloader::class,
        Bootloader\TwigExtensionBootloader::class,
        StemplerBootloader::class,
        SerializerBootloader::class,

        EventsBootloader::class,
        EventBusBootloader::class,

        // Framework commands
        Framework\CommandBootloader::class,
        Scaffolder\ScaffolderBootloader::class,

        // Debug and debug extensions
        Framework\DebugBootloader::class,
        Framework\Debug\LogCollectorBootloader::class,
        Framework\Debug\HttpCollectorBootloader::class,

        RoadRunnerBridge\CommandBootloader::class,

        Bootloader\SecurityBootloader::class,

        \Spiral\DatabaseSeeder\Bootloader\DatabaseSeederBootloader::class,
        \Spiral\TemporalBridge\Bootloader\TemporalBridgeBootloader::class,
        \Spiral\Cqrs\Bootloader\CqrsBootloader::class,
    ];

    /*
     * Application specific services and extensions.
     */
    protected const APP = [
        SharedBootloader::class,
        Bootloader\RoutesBootloader::class,

        // fast code prototyping
        Prototype\PrototypeBootloader::class,

        Bootloader\AppBootloader::class,
    ];
}
