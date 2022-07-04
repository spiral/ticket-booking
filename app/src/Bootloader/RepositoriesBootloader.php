<?php

declare(strict_types=1);

namespace App\Bootloader;

use App\Repository\Auditorium\ReservedSeatRepositoryInterface;
use App\Repository\Auditorium\SeatRepositoryInterface;
use App\Repository\AuditoriumRepositoryInterface;
use App\Repository\MovieRepositoryInterface;
use App\Repository\Postgres\Auditorium\ReservedSeatRepository;
use App\Repository\Postgres\Auditorium\SeatRepository;
use App\Repository\Postgres\AuditoriumRepository;
use App\Repository\Postgres\MovieRepository;
use App\Repository\Postgres\Reservation\TypeRepository;
use App\Repository\Postgres\ReservationRepository;
use App\Repository\Postgres\ScreeningRepository;
use App\Repository\Reservation\TypeRepositoryInterface;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\ScreeningRepositoryInterface;
use Spiral\Boot\Bootloader\Bootloader;

final class RepositoriesBootloader extends Bootloader
{
    protected const BINDINGS = [
        ScreeningRepositoryInterface::class => ScreeningRepository::class,
        ReservationRepositoryInterface::class => ReservationRepository::class,
        AuditoriumRepositoryInterface::class => AuditoriumRepository::class,
        MovieRepositoryInterface::class => MovieRepository::class,
        TypeRepositoryInterface::class => TypeRepository::class,
        SeatRepositoryInterface::class => SeatRepository::class,
        ReservedSeatRepositoryInterface::class => ReservedSeatRepository::class,
    ];
}
