<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Services\Mappers\MoneyFactory;
use Spiral\Cqrs\Attribute\CommandHandler;
use Spiral\Shared\GRPC\RequestContext;
use Spiral\Shared\Services\Payment\v1\DTO\ChargeRequest;
use Spiral\Shared\Services\Payment\v1\DTO\Payment;
use Spiral\Shared\Services\Payment\v1\DTO\Receipt;
use Spiral\Shared\Services\Payment\v1\PaymentServiceInterface;

final class ChargeMoneyHandler
{
    public function __construct(
        private readonly PaymentServiceInterface $client
    ) {
    }

    #[CommandHandler]
    public function __invoke(ChargeMoneyCommand $command): Receipt
    {
        $response = $this->client->Charge(
            new RequestContext(),
            new ChargeRequest([
                'payment' => new Payment([
                    'description' => $command->description,
                    'payment_method' => $command->paymentMethod,
                    'email' => $command->email,
                    'money' => MoneyFactory::fromMoney($command->money),
                ]),
            ])
        );

        return $response->getReceipt();
    }
}
