<?php

declare(strict_types=1);

namespace App\Application\Command;

use Spiral\Cqrs\Attribute\CommandHandler;
use Spiral\Shared\GRPC\RequestContext;
use Spiral\Shared\Services\Cinema\v1\CinemaServiceInterface;
use Spiral\Shared\Services\Cinema\v1\DTO\ReserveRequest;
use Spiral\Shared\Services\Cinema\v1\DTO\Seat;

final class ReserveTicketHandler
{
    public function __construct(
        private readonly CinemaServiceInterface $cinemaService,
    ) {
    }

    #[CommandHandler]
    public function __invoke(ReserveTicketCommand $command): array
    {
        $response = $this->cinemaService->Reserve(
            new RequestContext(),
            new ReserveRequest([
                'screening_id' => $command->screeningId,
                'reservation_type_id' => $command->reservationTypeId,
                'user_id' => $command->userId,
                'seats' => \array_map(fn(int $seatId) => new Seat(['id' => $seatId]), $command->seatIds),
            ])
        );

        return [
            'id' => $response->getReservation()->getUuid(),
            'expires_at' => $response->getReservation()->getExpiresAt()->toDateTime()->getTimestamp()
        ];
    }
}
