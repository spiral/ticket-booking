<?php

declare(strict_types=1);

namespace App\UI\Web\Controller\Api\Centrifugo;

use Spiral\Auth\AuthScope;
use Spiral\Http\Request\InputManager;
use Spiral\Router\Annotation\Route;

final class PublishAction
{
    #[Route('/centrifugo/publish', name: 'api.centrifugo.publish', methods: 'POST', group: 'api')]
    public function __invoke(
        InputManager $input,
        AuthScope $authScope
    ): array {
        return [
            'result' => new \stdClass(),
        ];
    }
}
