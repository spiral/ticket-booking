<?php

declare(strict_types=1);

namespace App\UI\Web\Controller\Api\Centrifugo;

use Spiral\Auth\AuthScope;
use Spiral\Http\Request\InputManager;
use Spiral\Router\Annotation\Route;

final class SubscribeAction
{
    #[Route('/centrifugo/subscribe', name: 'api.centrifugo.subscribe', methods: 'POST', group: 'centrifugo')]
    public function __invoke(InputManager $input, AuthScope $authScope): array
    {
        $channel = $input->data('channel');

        return [
            'result' => new \stdClass()
        ];
    }
}
