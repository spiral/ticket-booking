<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Application\Command\SendEmailCommand;
use App\Services\Mappers\MoneyFactory;
use App\Services\Mappers\TimestampFactory;
use Ramsey\Uuid\Uuid;
use Spiral\Cqrs\CommandBusInterface;
use Spiral\RoadRunner\Metrics\MetricsInterface;
use Spiral\RoadRunner\GRPC;
use Spiral\Shared\Services\Payment\v1\DTO\ChargeRequest;
use Spiral\Shared\Services\Payment\v1\DTO\ChargeResponse;
use Spiral\Shared\Services\Payment\v1\DTO\Receipt;
use Spiral\Shared\Services\Payment\v1\PaymentServiceInterface;
use Spiral\Telemetry\SpanInterface;
use Spiral\Telemetry\TracerInterface;

final class PaymentService implements PaymentServiceInterface
{
    public function __construct(
        private readonly TracerInterface $tracer,
        private readonly CommandBusInterface $commandBus,
        private readonly MetricsInterface $metrics
    ) {
    }

    public function Charge(
        GRPC\ContextInterface $ctx,
        ChargeRequest $in
    ): ChargeResponse {
        $payment = $in->getPayment();

        /** @var Receipt $receipt */
        $receipt = $this->tracer->trace(
            'Charge money',
            function (SpanInterface $span) use ($payment): Receipt {
                $receipt = new Receipt();

                $receipt->setId(Uuid::uuid4()->toString());
                $receipt->setTransactionId(Uuid::uuid4()->toString());
                $receipt->setMoney($payment->getMoney());
                $receipt->setFee(MoneyFactory::create(1000));
                $receipt->setCreatedAt(TimestampFactory::now());

                $span->setAttributes([
                    'amount' => $payment->getMoney()->getAmount(),
                    'currency' => $payment->getMoney()->getCurrency(),
                    'description' => $payment->getDescription(),
                    'payment_method' => $payment->getPaymentMethod(),
                ]);

                return $receipt;
            },
        );

        $this->metrics->add('total_transactions', 1);
        $this->metrics->add('money_sum', $payment->getMoney()->getAmount());
        $this->metrics->add('money_fee_sum', $receipt->getFee()->getAmount());

        $this->commandBus->dispatch(
            new SendEmailCommand(
                template: 'receipt.dark.php',
                email: $payment->getEmail(),
                data: [
                    'id' => $receipt->getId(),
                    'email' => $payment->getEmail(),
                    'description' => $payment->getDescription(),
                    'transaction_id' => $receipt->getTransactionId(),
                    'amount' => $receipt->getMoney()->getAmount() . $receipt->getMoney()->getCurrency(),
                    'fee' => $receipt->getFee()->getAmount() . $receipt->getFee()->getCurrency(),
                ]
            )
        );

        return new ChargeResponse([
            'receipt' => $receipt,
        ]);
    }
}
