<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Repository\ScreeningRepositoryInterface;
use Spiral\Cqrs\Attribute\QueryHandler;
use Spiral\Shared\GRPC\RequestContext;
use Spiral\Shared\Services\Cinema\v1\CinemaServiceInterface;
use Spiral\Shared\Services\Cinema\v1\DTO\ScheduleRequest;
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
        $response = $this->cinemaService->Schedule(new RequestContext(), new ScheduleRequest());

        $result = [];
        /** @var Screening[] $screenings */
        $screenings = $response->getScreenings();

        foreach ($screenings as $screening) {
            $result[] = [
                'id' => $screening->getId(),
                'movie' => $screening->getMovie()->getTitle(),
                'auditorium' => $screening->getAuditorium(),
                'total_seats' => $screening->getTotalSeats(),
                'price' => $screening->getPrice()->getAmount() . $screening->getPrice()->getCurrency(),
                'starts_at' => $screening->getStartsAt()->toDateTime(),
            ];
        }

        return $result;
    }
}
