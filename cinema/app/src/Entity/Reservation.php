<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Auditorium\ReservedSeat;
use App\Entity\Reservation\Type;
use App\Repository\Postgres\ReservationRepository;
use App\ValueObject\Money;
use Carbon\Carbon;
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

    #[Column(type: 'datetime', name: 'expires_at')]
    private \DateTimeInterface $expiresAt;

    #[Column(type: 'datetime', name: 'paid_at', nullable: true)]
    private ?\DateTimeInterface $paidAt = null;

    #[Column(type: 'datetime', name: 'canceled_at', nullable: true)]
    private ?\DateTimeInterface $canceledAt = null;

    #[Column(type: 'string', name: 'transaction_id', nullable: true)]
    private ?string $transactionId = null;

    public function __construct(
        #[Column(type: 'string', name: 'uuid', primary: true)]
        private string $uuid,
        #[BelongsTo(target: Screening::class)]
        private Screening $screening,
        #[BelongsTo(target: Type::class)]
        private Type $type,
        #[Column(type: 'bigInteger', name: 'user_id')]
        private int $userId
    ) {
        $this->createdAt = new \DateTimeImmutable();
        $this->expiresAt = Carbon::now()->addMinutes(3)->toDateTimeImmutable();
    }

    public function getTotalCost(): Money
    {
        return new Money(
            $this->getScreening()->getPrice()->getValue() * count($this->getSeats())
        );
    }

    public function markAsPaid(string $transactionId): void
    {
        $this->transactionId = $transactionId;
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

    public function getUserId(): int
    {
        return $this->userId;
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

    public function reserveSeat(ReservedSeat $seat): void
    {
        $this->seats[] = $seat;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function getExpiresAt(): \DateTimeInterface
    {
        return $this->expiresAt;
    }
}
