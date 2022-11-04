<?php

declare(strict_types=1);

namespace App\Centrifuge;

use RoadRunner\Centrifugo\ConnectRequest;
use RoadRunner\Centrifugo\Payload\ConnectResponse;
use RoadRunner\Centrifugo\RequestInterface;
use Spiral\RoadRunnerBridge\Centrifugo\ServiceInterface;

class ConnectService implements ServiceInterface
{
    /**
     * @param ConnectRequest $request
     */
    public function handle(RequestInterface $request): void
    {
        try {
            $request->respond(
                new ConnectResponse(
                    user: (string) $request->getAttribute('user_id'),
                )
            );
        } catch (\Throwable $e) {
            $request->error($e->getCode(), $e->getMessage());
        }
    }
}
