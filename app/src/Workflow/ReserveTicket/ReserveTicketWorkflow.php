<?php

declare(strict_types=1);

namespace App\Workflow\ReserveTicket;

use Carbon\CarbonInterval;
use Temporal\Activity\ActivityOptions;
use Temporal\Internal\Workflow\ActivityProxy;
use Temporal\Workflow;

class ReserveTicketWorkflow implements ReserveTicketWorkflowInterface
{
    private ReservationSettings $settings;
    private bool $paid = false;

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
        array $seatIds
    ) {
        $this->settings = new ReservationSettings(
            $reservationId
        );

        // Create reservation
        $expiresInSeconds = yield $this->reservation->reserve(
            $this->settings->reservationId,
            $screeningId,
            $reservationTypeId,
            $seatIds
        );

        $expired = yield Workflow::awaitWithTimeout(
            CarbonInterval::seconds($expiresInSeconds),
            fn() => $this->expired,
            fn() => $this->paid
        );

        if ($expired) {
            $this->expired = true;
        }

        if ($this->paid) {
            return true;
        }

        if ($this->expired) {
            yield $this->reservation->cancel($this->settings->reservationId);

            return null;
        }

        return true;
    }

    public function pay(): \Generator
    {
        if ($this->expired) {
            // handle an exception
            throw new \Exception('Reservation is expired');
        }

        $this->seats = yield $this->paymentGateway->pay($this->settings->reservationId);
        yield $this->mailer->sendTicketsByMail($this->settings->reservationId);

        $this->paid = \count($this->seats) > 0;
    }

    public function cancel(): \Generator
    {
        $this->expired = true;
    }

    public function isExpired(): bool
    {
        return $this->expired;
    }

    public function isPaid(): bool
    {
        return $this->paid;
    }
}
