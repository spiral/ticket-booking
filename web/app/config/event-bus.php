<?php

declare(strict_types=1);

use App\UI\Web\Interceptor\BroadcastEventInterceptor;

return [
    'interceptors' => [
        BroadcastEventInterceptor::class
    ]
];
