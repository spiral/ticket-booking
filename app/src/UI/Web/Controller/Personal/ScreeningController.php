<?php

/**
 * This file is part of Spiral package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\UI\Web\Controller\Personal;

use App\Application\Query\ActiveScreeningsQuery;
use App\Entity\Screening;
use App\UI\Web\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface;
use Spiral\Domain\Annotation\Guarded;
use Spiral\Router\Annotation\Route;

#[Guarded(permission: 'personal.screening')]
class ScreeningController extends AbstractController
{
    #[Route('/personal/screenings', name: 'screenings', methods: 'GET', group: 'personal')]
    public function screenings(): ResponseInterface
    {
        return $this->render('reservation/screenings', ['screenings' => $this->ask(new ActiveScreeningsQuery())]);
    }

    #[Route('/personal/seats/<id:\d+>', name: 'seats', methods: 'GET', group: 'personal')]
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
