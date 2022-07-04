<?php

declare(strict_types=1);

namespace App\Workflow\ReserveTicket;

use Carbon\CarbonInterval;
use Ramsey\Uuid\Uuid;
use Temporal\Activity\ActivityOptions;
use Temporal\Internal\Workflow\ActivityProxy;
use Temporal\Workflow;

class ReserveTicketWorkflow implements ReserveTicketWorkflowInterface
{
    private ReservationSettings $settings;

    /** @var ActivityProxy|ReserveTicketActivityInterface */
    private ActivityProxy $activity;

    public function __construct()
    {
        $this->activity = Workflow::newActivityStub(
            ReserveTicketActivityInterface::class,
            ActivityOptions::new()
                ->withScheduleToCloseTimeout(CarbonInterval::seconds(10))
        );
    }

    public function reserve(
        string $reservationId,
        int $screeningId,
        int $reservationTypeId,
        array $seatIds
    ) {
        $this->settings = new ReservationSettings(
            $reservationId,
            CarbonInterval::minutes(1)
        );

        // Create reservation
        yield $this->activity->reserve(
            $this->settings->reservationId,
            $screeningId,
            $reservationTypeId,
            $seatIds
        );

        yield Workflow::timer($this->settings->ttl);
        yield $this->activity->cancel($this->settings->reservationId);

        return $this->settings->reservationId;
    }
}
