<?php

declare(strict_types=1);

use Spiral\Broadcasting\Driver\LogBroadcast;
use Spiral\Broadcasting\Driver\NullBroadcast;
use Spiral\Security\Actor\Actor;

return [
    'default' => env('BROADCAST_CONNECTION', 'null'),
    'authorize' => [
        'path' => env('BROADCAST_AUTHORIZE_PATH'),
        'topics' => [
            'user.{id}' => static fn($id, Actor $actor): bool => $actor->getId() === $id,
        ],
    ],
    'aliases' => [],
    'connections' => [
        'centrifugo' => [
            'driver' => 'centrifugo',
        ],
        'log' => [
            'driver' => 'log',
        ],
        'null' => [
            'driver' => 'null',
        ],
    ],
    'driverAliases' => [
        'null' => NullBroadcast::class,
        'log' => LogBroadcast::class,
        'centrifugo' => \Spiral\Shared\Broadcasting\CentrifugoBroadcast::class,
    ],
];
