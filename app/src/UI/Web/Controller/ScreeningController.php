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
use App\Entity\Screening;
use Psr\Http\Message\ResponseInterface;
use Spiral\Router\Annotation\Route;

class ScreeningController extends AbstractController
{
    #[Route('/screenings', name: 'screenings', methods: 'GET')]
    public function screenings(): ResponseInterface
    {
        return $this->render('reservation/screenings', ['screenings' => $this->ask(new ActiveScreeningsQuery())]);
    }

    #[Route('/seats/<id:\d+>', name: 'seats', methods: 'GET')]
    public function seats(Screening $screening): ResponseInterface
    {
        $seats = [];
        foreach ($screening->getAuditorium()->getSeats() as $seat) {
            $seats[$seat->getRow()][] = $seat;
            \asort($seats[$seat->getRow()]);
        }
        \asort($seats);

        return $this->render('reservation/seats', [
            'seats' => $seats,
            'reserved' => $screening->getReservedSeats(),
            'screening_id' => $screening->getId()
        ]);
    }
}
