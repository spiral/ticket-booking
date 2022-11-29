<?php

declare(strict_types=1);

use App\Centrifuge\ConnectService;
use App\Centrifuge\RPCService;
use App\Centrifuge\SubscribeService;
use RoadRunner\Centrifugo\Request\RequestType;
use App\Centrifuge\Interceptor;

return [
    'services' => [
        RequestType::Connect->value => ConnectService::class,
        RequestType::Subscribe->value => SubscribeService::class,
        RequestType::RPC->value => RPCService::class,
    ],
    'interceptors' => [
        RequestType::Connect->value => [
            Interceptor\LoggingInterceptor::class,
            Interceptor\TelemetryInterceptor::class,
            Interceptor\AuthInterceptor::class,
        ],
        RequestType::Subscribe->value => [
            Interceptor\LoggingInterceptor::class,
            Interceptor\TelemetryInterceptor::class,
            Interceptor\AuthInterceptor::class,
        ],
        RequestType::RPC->value => [
            Interceptor\LoggingInterceptor::class,
            Interceptor\TelemetryInterceptor::class,
            Interceptor\AuthInterceptor::class,
        ],
    ],
];
