<?php

declare(strict_types=1);

namespace App\UI\Web\Interceptor;

use App\Broadcast\ShouldBroadcastInterface;
use Spiral\Broadcasting\BroadcastInterface;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;
use Spiral\Serializer\SerializerInterface;

final class BroadcastEventInterceptor implements CoreInterceptorInterface
{
    public function __construct(
        private readonly BroadcastInterface $broadcast,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        $event = $parameters['event'];
        $result = $core->callAction($controller, $action, $parameters);

        if ($event instanceof ShouldBroadcastInterface) {
            $this->broadcast->publish(
                $event->getBroadcastTopics(),
                $this->serializer->serialize(['event' => $event::class, 'data' => $event->getPayload()])
            );
        }

        return $result;
    }
}
