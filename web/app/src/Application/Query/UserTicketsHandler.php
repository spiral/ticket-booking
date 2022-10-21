<?php

declare(strict_types=1);

namespace App\Application\Query;

use Spiral\Cqrs\Attribute\QueryHandler;
use Spiral\Shared\GRPC\RequestContext;
use Spiral\Shared\Services\Cinema\v1\CinemaServiceInterface;
use Spiral\Shared\Services\Cinema\v1\DTO\Reservation;
use Spiral\Shared\Services\Cinema\v1\DTO\ReservationsRequest;
use Spiral\Shared\Services\Cinema\v1\DTO\Seat;

final class UserTicketsHandler
{
    public function __construct(
        private readonly CinemaServiceInterface $cinemaService,
    ) {
    }

    #[QueryHandler]
    public function __invoke(UserTicketsQuery $query): iterable
    {
        $response = $this->cinemaService->Reservations(
            new RequestContext(),
            new ReservationsRequest([
                'user_id' => $query->userId,
            ])
        );

        return \array_map(fn(Reservation $reservation) => [
            'uuid' => $reservation->getUuid(),
            'screening' => [
                'movie' => $reservation->getScreening()->getMovie()->getTitle(),
                'starts_at' => $reservation->getScreening()->getStartsAt()->toDateTime()->getTimestamp(),
            ],
            'seats' => \array_map(fn(Seat $seat) => [
                'row' => $seat->getRow(),
                'number' => $seat->getNumber()
            ], \iterator_to_array($reservation->getSeats()->getIterator())),
            'total_cost' => [
                'amount' => $reservation->getTotalCost()->getAmount(),
                'currency' => $reservation->getTotalCost()->getCurrency()
            ],
            'created_at' => $reservation->getCreatedAt()->toDateTime()->getTimestamp(),
            'paid_at' => $reservation->getPaidAt() ? $reservation->getPaidAt()->toDateTime()->getTimestamp() : null,
            'expires_at' => $reservation->getExpiresAt()->toDateTime()->getTimestamp(),
        ], \iterator_to_array($response->getReservations()));
    }
}
