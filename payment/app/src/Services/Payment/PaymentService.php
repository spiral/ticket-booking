<?php

declare(strict_types=1);

namespace App\Services\Payment;

use Ramsey\Uuid\Uuid;
use Spiral\Cqrs\CommandBusInterface;
use App\CQRS\Command\SendEmailCommand;
use App\Mappers\MoneyFactory;
use App\Mappers\TimestampFactory;
use Spiral\RoadRunner\GRPC;
use GRPC\Services\Payment\DTO\ChargeRequest;
use GRPC\Services\Payment\DTO\ChargeResponse;
use GRPC\Services\Payment\DTO\Receipt;
use GRPC\Services\Payment\PaymentServiceInterface;

final class PaymentService implements PaymentServiceInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
    }

    public function Charge(
        GRPC\ContextInterface $ctx,
        ChargeRequest $in
    ): ChargeResponse {
        $payment = $in->getPayment();

        $receipt = new Receipt([
            'id' => Uuid::uuid4()->toString(),
            'transaction_id' => Uuid::uuid4()->toString(),
            'money' => $payment->getMoney(),
            'fee' => MoneyFactory::create(1, 'USD'),
            'created_at' => (TimestampFactory::now(),
        ]);

        $this->commandBus->dispatch(
            new SendEmailCommand(
                template: 'receipt.dark.php',
                email: $payment->getEmail(),
                data: [
                    'receipt' => $receipt
                ]
            )
        );

        return new ChargeResponse(['receipt' => $receipt]);
    }
}
