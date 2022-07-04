<?php

declare(strict_types=1);

namespace App\Workflow\BuyTicket;

use App\Workflow\ReserveTicket\BuyTicketActivityInterface;
use Carbon\CarbonInterval;
use Temporal\Activity\ActivityOptions;
use Temporal\Internal\Workflow\ActivityProxy;
use Temporal\Workflow;

class CancelTicketWorkflow implements CancelTicketWorkflowInterface
{
    /** @var ActivityProxy|BuyTicketActivityInterface */
    private ActivityProxy $activity;
    /** @var int[] */
    private array $seats;

    public function __construct()
    {
        $this->activity = Workflow::newActivityStub(
            BuyTicketActivityInterface::class,
            ActivityOptions::new()
                ->withScheduleToCloseTimeout(CarbonInterval::seconds(10))
        );
    }

    public function cancel(string $reservationId)
    {
        $this->seats = yield $this->activity->buy($reservationId);

        return true;
    }
}
