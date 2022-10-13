<?php

declare(strict_types=1);

namespace App\Application\Command;

use Spiral\Cqrs\Attribute\CommandHandler;
use Spiral\Shared\GRPC\RequestContext;
use Spiral\Shared\Services\Cinema\v1\CinemaServiceInterface;
use Spiral\Shared\Services\Cinema\v1\DTO\CancelRequest;

final class CancelTicketHandler
{
    public function __construct(
        private readonly CinemaServiceInterface $cinemaService,
    ) {
    }

    #[CommandHandler]
    public function __invoke(CancelTicketCommand $command): void
    {
        $this->cinemaService->Cancel(
            new RequestContext(),
            new CancelRequest([
                'reservation_id' => $command->reservationId->toString(),
            ])
        );
    }
}
