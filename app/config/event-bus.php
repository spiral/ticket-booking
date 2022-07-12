<?php

declare(strict_types=1);

use App\UI\Web\Interceptor\BroadcastEventInterceptor;

return [
    'discoverListeners' => env('EVENT_BUS_DISCOVER_LISTENERS', true),
    'interceptors' => [
        BroadcastEventInterceptor::class
    ]
];
