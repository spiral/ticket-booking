<?php

declare(strict_types=1);

namespace App\UI\Web\Controller\Api\Centrifugo;

use Spiral\Auth\AuthScope;
use Spiral\Http\Request\InputManager;
use Spiral\Router\Annotation\Route;

final class RefreshTokenAction
{
    #[Route('/centrifugo/refresh', name: 'api.centrifugo.refresh', methods: 'POST', group: 'api')]
    public function __invoke(InputManager $input, AuthScope $authScope): array
    {
        //dumprr($input->headers);

        return [];
    }
}
