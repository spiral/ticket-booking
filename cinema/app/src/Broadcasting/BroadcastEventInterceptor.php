<?php

declare(strict_types=1);

namespace App\Broadcasting;

use Spiral\Broadcasting\BroadcastInterface;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;
use Spiral\Queue\SerializerRegistryInterface;

final class BroadcastEventInterceptor implements CoreInterceptorInterface
{
    public function __construct(
        private readonly BroadcastInterface $broadcast,
        private readonly SerializerRegistryInterface $registry
    ) {
    }

    public function process(string $controller, string $action, array $parameters, CoreInterface $core): mixed
    {
        $event = $parameters['event'];
        $result = $core->callAction($controller, $action, $parameters);

        if ($event instanceof ShouldBroadcastInterface) {
            // TODO Add exception handling
            $this->broadcast->publish(
                $event->getBroadcastTopics(),
                $this->registry->getSerializer('json')->serialize(
                    ['event' => $event->getEventName(), 'data' => $event->getPayload()]
                )
            );
        }

        return $result;
    }
}
