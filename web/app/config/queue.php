<?php

declare(strict_types=1);

use Spiral\Queue\Driver\SyncDriver;
use Spiral\RoadRunner\Jobs\Queue\MemoryCreateInfo;
use Spiral\RoadRunnerBridge\Queue\Queue;

return [
    /**
     *  Default queue connection name
     */
    'default' => env('QUEUE_CONNECTION', 'sync'),

    /**
     *  Aliases for queue connections, if you want to use domain specific queues
     */
    'aliases' => [
        // 'mail-queue' => 'roadrunner',
        // 'rating-queue' => 'sync',
    ],


    'pipelines' => [
        'memory' => [
            'connector' => new MemoryCreateInfo('local'),
            // Run consumer for this pipeline on startup (by default)
            // You can pause consumer for this pipeline via console command
            // php app.php queue:pause local
            'consume' => true,
        ],
    ],

    /**
     * Queue connections
     * Drivers: "sync", "roadrunner"
     */
    'connections' => [
        'sync' => [
            // Job will be handled immediately without queueing
            'driver' => 'sync',
        ],
        'roadrunner' => [
            'driver' => 'roadrunner',
            'default' => 'memory',
        ],
    ],

    'driverAliases' => [
        'sync' => SyncDriver::class,
        'roadrunner' => Queue::class,
    ],

    'registry' => [
        'handlers' => [],
    ],
];
