<?php

declare(strict_types=1);

namespace App\Application\Query;

use Spiral\Cqrs\Attribute\QueryHandler;
use Spiral\Shared\GRPC\RequestContext;
use Spiral\Shared\Services\Cinema\v1\CinemaServiceInterface;
use Spiral\Shared\Services\Cinema\v1\DTO\ScreeningRequest;

final class ScreeningByIdHandler
{
    public function __construct(
        private readonly CinemaServiceInterface $cinemaService
    ) {
    }

    #[QueryHandler]
    public function __invoke(ScreeningByIdQuery $query): array
    {
        $response = $this->cinemaService->Screening(
            new RequestContext(),
            new ScreeningRequest([
                'id' => $query->screeningId,
            ])
        );

        $screening = $response->getScreening();

        $reservedSeats = [];
        foreach ($screening->getReservedSeats() as $seat) {
            $reservedSeats[] = $seat->getId();
        }

        $seats = [];
        foreach ($screening->getAuditorium()->getSeats() as $seat) {
            $seats[$seat->getRow()][] = [
                'id' => $seat->getId(),
                'row' => $seat->getRow(),
                'number' => $seat->getNumber(),
                'reserved' => \in_array($seat->getId(), $reservedSeats),
            ];
            \asort($seats[$seat->getRow()]);
        }
        \asort($seats);

        return [
            'id' => $screening->getId(),
            'movie' => [
                'id' => $screening->getMovie()->getId(),
                'title' => $screening->getMovie()->getTitle(),
                'description' => $screening->getMovie()->getDescription(),
                'duration' => $screening->getMovie()->getDuration(),
            ],
            'auditorium' => [
                'id' => $screening->getAuditorium()->getId(),
                'name' => $screening->getAuditorium()->getName(),
            ],
            'starts_at' => $screening->getStartsAt()->toDateTime()->getTimestamp(),
            'price' => [
                'amount' => $screening->getPrice()->getAmount(),
                'currency' => $screening->getPrice()->getCurrency()
            ],
            'seats' => $seats,
            'reserved_seats' => $reservedSeats
        ];
    }
}
