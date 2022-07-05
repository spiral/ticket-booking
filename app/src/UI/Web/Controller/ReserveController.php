<?php

/**
 * This file is part of Spiral package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\UI\Web\Controller;

use App\Application\Command\ReserveTicketCommand;
use App\UI\Web\Request\ReserveTicketRequest;
use Psr\Http\Message\ResponseInterface;
use Spiral\Router\Annotation\Route;

class ReserveController extends AbstractController
{
    #[Route('/reserve', name: 'reserve', methods: 'POST')]
    public function reserve(ReserveTicketRequest $request): ResponseInterface
    {
        $command = new ReserveTicketCommand(
            $request->screeningId,
            $request->reservationTypeId,
            $request->seatIds
        );

        try {
            $this->commandBus->dispatch($command);
        } catch (\Throwable $e) {
            return $this->json(['errors' => [$e->getMessage()]]);
        }

        return $this->json([
            'reservationId' => $command->reservationId->toString(),
            'message' => $this->translator->trans('Reservation started.')
        ]);
    }
}
