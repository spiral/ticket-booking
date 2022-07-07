<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Auditorium\ReservedSeat;
use App\Entity\Reservation\Type;
use App\Repository\Postgres\ReservationRepository;
use App\Repository\ReservationRepositoryInterface;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Relation\HasMany;

#[Entity(
    // repository: ReservationRepositoryInterface::class
    repository: ReservationRepository::class
)]
class Reservation
{
    #[HasMany(target: ReservedSeat::class)]
    private array $seats = [];

    #[Column(type: 'datetime', name: 'created_at')]
    private \DateTimeInterface $createdAt;

    #[Column(type: 'datetime', name: 'paid_at', nullable: true)]
    private ?\DateTimeInterface $paidAt = null;

    #[Column(type: 'datetime', name: 'canceled_at', nullable: true)]
    private ?\DateTimeInterface $canceledAt = null;

    public function __construct(
        #[Column(type: 'string', name: 'uuid', primary: true)]
        private string $uuid,
        #[BelongsTo(target: Screening::class)]
        private Screening $screening,
        #[BelongsTo(target: Type::class)]
        private Type $type,
        #[BelongsTo(target: User::class)]
        private User $user
    ) {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function markAsPaid(): void
    {
        $this->paidAt = new \DateTimeImmutable();
    }

    public function isPaid(): bool
    {
        return $this->paidAt !== null;
    }

    public function isActive(): bool
    {
        return $this->canceledAt === null;
    }

    public function isCanceled(): bool
    {
        return $this->canceledAt !== null;
    }

    public function markAsCanceled(): void
    {
        $this->canceledAt = new \DateTimeImmutable();
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getScreening(): Screening
    {
        return $this->screening;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return ReservedSeat[]
     */
    public function getSeats(): array
    {
        return $this->seats;
    }

    public function getCanceledAt(): ?\DateTimeInterface
    {
        return $this->canceledAt;
    }

    public function getPaidAt(): ?\DateTimeInterface
    {
        return $this->paidAt;
    }
}
