<?php

declare(strict_types=1);

namespace App\Application\Command;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Spiral\Cqrs\CommandInterface;
use Webmozart\Assert\Assert;

final class ReserveTicketCommand implements CommandInterface
{
    public readonly UuidInterface $reservationId;

    public function __construct(
        public readonly int $screeningId,
        public readonly int $reservationTypeId,
        public readonly int $userId,
        public readonly array $seatIds
    ) {
        Assert::minCount($this->seatIds, 1, 'At leas one seat should be chosen.');
        $this->reservationId = Uuid::uuid4();
    }
}
