<?php

declare(strict_types=1);

namespace App\Workflow\ReserveTicket;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Temporal\Activity\ActivityOptions;
use Temporal\Internal\Workflow\ActivityProxy;
use Temporal\Workflow;

class ReserveTicketWorkflow implements ReserveTicketWorkflowInterface
{
    private ReservationSettings $settings;
    private bool $paid = false;
    private int $expiresAt = 0;

    /** @var ActivityProxy|ReserveTicketActivityInterface */
    private ActivityProxy $reservation;
    /** @var ActivityProxy|ReserveTicketActivityInterface */
    private ActivityProxy $paymentGateway;
    private bool $expired = false;
    private array $seats = [];

    public function __construct()
    {
        $this->reservation = Workflow::newActivityStub(
            ReserveTicketActivityInterface::class,
            ActivityOptions::new()
                ->withScheduleToCloseTimeout(CarbonInterval::seconds(10))
        );

        $this->paymentGateway = Workflow::newActivityStub(
            BuyTicketActivityInterface::class,
            ActivityOptions::new()
                ->withScheduleToCloseTimeout(CarbonInterval::seconds(10))
        );
    }

    public function reserve(
        string $reservationId,
        int $screeningId,
        int $reservationTypeId,
        int $userId,
        array $seatIds
    ) {
        $this->settings = new ReservationSettings(
            $reservationId
        );

        // Create reservation
        $expiresTimestamp = yield $this->reservation->reserve(
            $reservationId,
            $screeningId,
            $reservationTypeId,
            $userId,
            $seatIds
        );

        $this->expiresAt = $expiresTimestamp;

        $expired = yield Workflow::awaitWithTimeout(
            Carbon::createFromTimestamp($expiresTimestamp)->diffAsCarbonInterval(),
            fn() => $this->expired,
            fn() => $this->paid
        );

        if ($this->paid) {
            return true;
        }

        if ($expired === false) {
            $this->expired = true;
        }

        if ($this->expired) {
            yield $this->reservation->cancel($this->settings->reservationId);

            return null;
        }

        return true;
    }

    public function pay(string $transactionId)
    {
        if ($this->expired) {
            // handle an exception
            throw new \Exception('Reservation is expired');
        }

        $this->seats = yield $this->paymentGateway->pay(
            $this->settings->reservationId, $transactionId
        );

        yield $this->paymentGateway->sendTicketsByMail($this->settings->reservationId);

        $this->paid = true;
    }

    public function cancel()
    {
        $this->expired = true;
    }

    public function isExpired(): bool
    {
        return $this->expired;
    }

    public function expiresIn(): int
    {
        return Carbon::createFromTimestamp($this->expiresAt)->diffInSeconds();
    }

    public function isPaid(): bool
    {
        return $this->paid;
    }

    public function getExpiresAt(): int
    {
        return $this->expiresAt;
    }
}
