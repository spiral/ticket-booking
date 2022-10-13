<?php

/**
 * This file is part of Spiral package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\UI\Web\Controller;

use Psr\Http\Message\ResponseInterface;
use Spiral\Router\Annotation\Route;

class WebsocketController extends AbstractController
{
    #[Route('/ws', name: 'ws.join', methods: 'GET')]
    public function index(): ResponseInterface
    {
        // TODO

        return $this->json([]);
    }
}
