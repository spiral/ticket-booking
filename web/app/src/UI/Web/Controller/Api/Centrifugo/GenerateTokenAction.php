<?php

declare(strict_types=1);

namespace App\UI\Web\Controller\Api\Centrifugo;

use Spiral\Auth\AuthScope;
use Spiral\Cqrs\CommandBusInterface;
use Spiral\Http\Request\InputManager;
use Spiral\Router\Annotation\Route;

final class GenerateTokenAction
{
    #[Route('/centrifugo/connect', name: 'api.centrifugo.connection_token', methods: 'POST', group: 'centrifugo')]
    public function __invoke(InputManager $input, AuthScope $authScope, CommandBusInterface $bus): array
    {
        return [
            'result' => ['user' => (string) $authScope->getActor()?->id]
        ];
    }
}
