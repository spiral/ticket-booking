<?php

declare(strict_types=1);

namespace App\Services\Payment;

use Google\Protobuf\Timestamp;
use Ramsey\Uuid\Uuid;
use Spiral\Shared\Services\Common\v1\DTO\Money;
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
        private readonly TracerInterface $tracer
    ) {
    }

    public function Charge(
        GRPC\ContextInterface $ctx,
        ChargeRequest $in
    ): ChargeResponse {
        $payment = $in->getPayment();

        $receipt = $this->tracer->trace(
            'Charge money',
            function (SpanInterface $span) use ($payment): Receipt {
                $receipt = new Receipt();

                $createdAt = new Timestamp();
                $createdAt->fromDateTime(new \DateTime());

                $receipt->setId(Uuid::uuid4()->toString());
                $receipt->setTransactionId(Uuid::uuid4()->toString());
                $receipt->setMoney($payment->getMoney());
                $receipt->setFee(
                    new Money([
                        'amount' => 1000,
                        'currency' => 'USD',
                    ])
                );
                $receipt->setCreatedAt($createdAt);

                $span->setAttributes([
                    'amount' => $payment->getMoney()->getAmount(),
                    'currency' => $payment->getMoney()->getCurrency(),
                    'description' => $payment->getDescription(),
                    'payment_method' => $payment->getPaymentMethod(),
                    'source' => $payment->getSource(),
                ]);

                return $receipt;
            },

        );

        return new ChargeResponse([
            'receipt' => $receipt,
            'status' => true,
        ]);
    }
}
