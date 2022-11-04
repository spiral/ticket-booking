<?php

declare(strict_types=1);

namespace App\Centrifuge;

use App\Application\Query;
use RoadRunner\Centrifugo\Payload\RPCResponse;
use RoadRunner\Centrifugo\RequestInterface;
use RoadRunner\Centrifugo\RPCRequest;
use Spiral\Cqrs\QueryBusInterface;
use Spiral\RoadRunnerBridge\Centrifugo\ServiceInterface;

final class RPCService implements ServiceInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {
    }

    /**
     * @param RPCRequest $request
     */
    public function handle(RequestInterface $request): void
    {
        $userId = $request->getAttribute('user_id');

        $result = match ($request->method) {
            'cinema.Schedule' => $this->queryBus->ask(new Query\ActiveScreeningsQuery()),
            'cinema.screening' => $this->queryBus->ask(new Query\ScreeningByIdQuery(screeningId: $request->data['id'])),
            'user.tickets' => $userId ? $this->queryBus->ask(
                new Query\UserTicketsQuery(userId: $userId)
            ) : ['error' => 'Unauthorized', 'code' => 401],
            default => ['error' => 'Not found', 'code' => 404]
        };

        try {
            $request->respond(
                new RPCResponse(
                    data: $result
                )
            );
        } catch (\Throwable $e) {
            $request->error($e->getCode(), $e->getMessage());
        }
    }
}
