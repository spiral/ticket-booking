<?php

declare(strict_types=1);

namespace App\Centrifuge;

use RoadRunner\Centrifugo\Payload\SubscribeResponse;
use RoadRunner\Centrifugo\RequestInterface;
use RoadRunner\Centrifugo\SubscribeRequest;
use Spiral\RoadRunnerBridge\Centrifugo\ServiceInterface;

final class SubscribeService implements ServiceInterface
{
    /**
     * @param SubscribeRequest $request
     */
    public function handle(RequestInterface $request): void
    {
        try {
            $request->respond(new SubscribeResponse());
        } catch (\Throwable $e) {
            $request->error($e->getCode(), $e->getMessage());
        }
    }
}
