<?php

declare(strict_types=1);

namespace App\UI\Web\Controller\Personal;

use App\Application\Query\ActiveScreeningsQuery;
use App\Application\Query\ScreeningByIdQuery;
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
        return $this->render('personal/screenings', [
            'screenings' => $this->ask(new ActiveScreeningsQuery()),
        ]);
    }

    #[Route('/personal/seats/<id:\d+>', name: 'seats', methods: 'GET', group: 'personal')]
    public function seats(string $id): ResponseInterface
    {
        return $this->render('personal/seats', [
            'screening' => $this->ask(new ScreeningByIdQuery((int)$id)),
        ]);
    }
}
