<?php

declare(strict_types=1);

use App\Broadcasting\BroadcastEventInterceptor;

return [
    'interceptors' => [
        BroadcastEventInterceptor::class
    ]
];
