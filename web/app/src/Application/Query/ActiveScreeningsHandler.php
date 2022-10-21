<?php

declare(strict_types=1);

namespace App\Application\Query;

use Spiral\Cqrs\Attribute\QueryHandler;
use Spiral\Shared\GRPC\RequestContext;
use Spiral\Shared\Services\Cinema\v1\CinemaServiceInterface;
use Spiral\Shared\Services\Cinema\v1\DTO\Screening;

final class ActiveScreeningsHandler
{
    public function __construct(
        private readonly CinemaServiceInterface $cinemaService
    ) {
    }

    #[QueryHandler]
    public function __invoke(ActiveScreeningsQuery $query): array
    {
        $response = $this->cinemaService->Schedule(new RequestContext(), new \Google\Protobuf\GPBEmpty());

        $result = [];
        /** @var Screening[] $screenings */
        $screenings = $response->getScreenings();

        foreach ($screenings as $screening) {
            $result[] = [
                'id' => $screening->getId(),
                'movie' => [
                    'title' => $screening->getMovie()->getTitle(),
                    'duration' => $screening->getMovie()->getDuration()
                ],
                'auditorium' => $screening->getAuditorium(),
                'total_seats' => $screening->getTotalSeats(),
                'price' => [
                    'amount' => $screening->getPrice()->getAmount(),
                    'currency' => $screening->getPrice()->getCurrency()
                ],
                'starts_at' => $screening->getStartsAt()->toDateTime()->getTimestamp(),
            ];
        }

        return $result;
    }
}
