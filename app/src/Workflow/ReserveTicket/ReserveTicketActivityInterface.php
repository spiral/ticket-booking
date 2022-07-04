<?php

declare(strict_types=1);

namespace App\Workflow\ReserveTicket;

use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

#[ActivityInterface(prefix: 'ReserveTicket.')]
interface ReserveTicketActivityInterface
{
    #[ActivityMethod]
    public function reserve(
        string $reservationId,
        int $screeningId,
        int $reservationTypeId,
        array $seatIds
    ): int;

    #[ActivityMethod]
    public function cancel(
        string $reservationId
    );
}
