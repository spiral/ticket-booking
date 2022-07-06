<?php

/**
 * This file is part of Spiral package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\UI\Web\Controller\Personal;

use App\Application\Command\BuyCommand;
use App\UI\Web\Controller\AbstractController;
use App\UI\Web\Request\BuyRequest;
use Psr\Http\Message\ResponseInterface;
use Spiral\Domain\Annotation\Guarded;
use Spiral\Router\Annotation\Route;

#[Guarded(permission: 'personal.user')]
class TicketController extends AbstractController
{
    #[Route('/tickets/buy', name: 'ticket.buy', methods: 'POST')]
    public function buy(BuyRequest $request): ResponseInterface
    {
        $command = new BuyCommand($request->reservationId);

        try {
            $this->commandBus->dispatch($command);
        } catch (\Throwable $e) {
            return $this->json(['errors' => [$e->getMessage()]]);
        }

        return $this->json([
            'message' => $this->translator->trans('Buying a ticket in progress.')
        ]);
    }
}
