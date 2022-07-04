<?php

declare(strict_types=1);

namespace App\Entity\Auditorium;

use App\Entity\Reservation;
use App\Entity\Screening;
use App\Repository\Auditorium\ReservedSeatRepositoryInterface;
use App\Repository\Postgres\Auditorium\ReservedSeatRepository;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Table\Index;

#[Entity(
    //repository: ReservedSeatRepositoryInterface::class,
    repository: ReservedSeatRepository::class,
    table: 'auditorium_reserved_seats'
)]
#[Index(columns: ['seat_id', 'reservation_uuid'], unique: true)]
class ReservedSeat
{
    #[Column(type: 'bigPrimary', name: 'id')]
    private int $id;

    #[BelongsTo(target: Screening::class)]
    private Screening $screening;

    public function __construct(
        #[BelongsTo(target: Seat::class)]
        private Seat $seat,
        #[BelongsTo(target: Reservation::class)]
        private Reservation $reservation,
    ) {
        $this->screening = $this->reservation->getScreening();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getReservation(): Reservation
    {
        return $this->reservation;
    }

    public function getSeat(): Seat
    {
        return $this->seat;
    }
}
