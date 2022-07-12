<?php

declare(strict_types=1);

use Psr\Log\LogLevel;
use Spiral\Broadcasting\Driver\LogBroadcast;
use Spiral\Core\Container\Autowire;
use Spiral\RoadRunnerBridge\Broadcasting\RoadRunnerBroadcast;
use Spiral\RoadRunnerBridge\Broadcasting\RoadRunnerGuard;
use Spiral\Security\Actor\Actor;

return [
    'default' => env('BROADCAST_CONNECTION', 'roadrunner'),

    'authorize' => [
        'path' => env('BROADCAST_AUTHORIZE_PATH'),
        'topics' => [
            'user.{id}' => static fn ($id, Actor $actor): bool => $actor->getId() === $id,
        ],
    ],

    'connections' => [
        'roadrunner' => [
            'driver' => 'roadrunner',
            'guard' => Autowire::wire(RoadRunnerGuard::class),
        ],
        'log' => [
            'driver' => 'log',
            'level' => LogLevel::INFO,
        ],
    ],
    'driverAliases' => [
        'log' => LogBroadcast::class,
        'roadrunner' => RoadRunnerBroadcast::class,
    ],
];
