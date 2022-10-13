<?php

/**
 * This file is part of Spiral package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\UI\Web\Controller\Personal;

use App\Application\Command\BuyTicketCommand;
use App\Application\Command\ReserveTicketCommand;
use App\UI\Web\Controller\AbstractController;
use App\UI\Web\Request\BuyRequest;
use App\UI\Web\Request\ReserveTicketRequest;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Spiral\Domain\Annotation\Guarded;
use Spiral\Router\Annotation\Route;

#[Guarded(permission: 'personal.ticket')]
class TicketController extends AbstractController
{
    #[Route('/personal/tickets/buy', name: 'ticket.buy', methods: 'POST', group: 'personal')]
    public function buy(BuyRequest $request): ResponseInterface
    {
        $command = new BuyTicketCommand(Uuid::fromString($request->reservationId));

        try {
            $receipt = $this->exec($command);
        } catch (\Throwable $e) {
            return $this->json(['errors' => [$e->getMessage()]]);
        }

        return $this->json([
            'message' => $this->translator->trans('Buying a ticket in progress.'),
            'receipt' => $receipt
        ]);
    }

    #[Route('/personal/tickets/reserve', name: 'reserve', methods: 'POST', group: 'personal')]
    public function reserve(ReserveTicketRequest $request): ResponseInterface
    {
        $command = new ReserveTicketCommand(
            $request->screeningId,
            $request->reservationTypeId,
            $this->authScope->getActor()->id,
            $request->seatIds
        );

        try {
            $result = $this->exec($command);
        } catch (\Throwable $e) {
            return $this->json(['errors' => [$e->getMessage()]]);
        }

        return $this->json([
            'reservation' => $result,
            'message' => $this->translator->trans('Reservation started.')
        ]);
    }
}
