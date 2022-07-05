<?php

/**
 * This file is part of Spiral package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\UI\Web\Controller;

use App\Entity\Screening;
use Psr\Http\Message\ResponseInterface;
use Spiral\Router\Annotation\Route;

class ReserveController extends AbstractController
{
    #[Route('/reserve/<id:\d+>', name: 'reserve', methods: 'GET')]
    public function reserve(Screening $screening): ResponseInterface
    {
        return $this->render('reservation/seats', [
            'seats' => $screening->getAuditorium()->getSeats(),
            'reserved' => $screening->getReservedSeats()
        ]);
    }
}
