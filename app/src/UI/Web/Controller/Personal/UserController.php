<?php

declare(strict_types=1);

namespace App\UI\Web\Controller\Personal;

use App\Application\Query\UserTicketsQuery;
use App\UI\Web\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface;
use Spiral\Domain\Annotation\Guarded;
use Spiral\Router\Annotation\Route;

#[Guarded(permission: 'personal.user')]
class UserController extends AbstractController
{
    #[Route('/personal/tickets', name: 'personal.tickets', methods: 'GET', group: 'personal')]
    public function tickets(): ResponseInterface
    {
        return $this->render('personal/tickets', [
            'tickets' => $this->ask(new UserTicketsQuery($this->authScope->getActor()->getId()))
        ]);
    }
}
