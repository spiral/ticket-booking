<?php

declare(strict_types=1);

namespace App\Workflow\BuyTicket;

use Carbon\CarbonInterval;
use Temporal\Activity\ActivityOptions;
use Temporal\Internal\Workflow\ActivityProxy;
use Temporal\Workflow;

class BuyTicketWorkflow implements BuyTicketWorkflowInterface
{
    /** @var ActivityProxy|BuyTicketActivityInterface */
    private ActivityProxy $activity;

    public function __construct()
    {
        $this->activity = Workflow::newActivityStub(
            BuyTicketActivityInterface::class,
            ActivityOptions::new()
                ->withScheduleToCloseTimeout(CarbonInterval::seconds(10))
        );
    }

    public function buy(string $reservationId)
    {
        return yield $this->activity->buy($reservationId);
    }
}
