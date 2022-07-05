<?php

/**
 * This file is part of Spiral package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\UI\Web\Controller;

use App\Application\Query\ActiveScreeningsQuery;
use Psr\Http\Message\ResponseInterface;
use Spiral\Router\Annotation\Route;

class ReserveController extends AbstractController
{
    #[Route('/screenings', name: 'screenings', methods: 'GET')]
    public function index(): ResponseInterface
    {
        return $this->render('reservation/screenings', ['screenings' => $this->ask(new ActiveScreeningsQuery())]);
    }
}
