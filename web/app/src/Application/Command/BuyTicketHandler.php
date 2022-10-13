<?php

declare(strict_types=1);

namespace App\Application\Command;

use Spiral\Cqrs\Attribute\CommandHandler;
use Spiral\Shared\GRPC\RequestContext;
use Spiral\Shared\Services\Cinema\v1\CinemaServiceInterface;
use Spiral\Shared\Services\Cinema\v1\DTO\PurchaseRequest;

final class BuyTicketHandler
{
    public function __construct(
        private readonly CinemaServiceInterface $cinemaService,
    ) {
    }

    #[CommandHandler]
    public function __invoke(BuyTicketCommand $command): array
    {
        $response = $this->cinemaService->Purchase(
            new RequestContext(),
            new PurchaseRequest([
                'reservation_id' => $command->reservationId->toString(),
            ])
        );

        return [
            'id' => $response->getReceipt()->getId(),
            'transaction_id' => $response->getReceipt()->getTransactionId(),
            'money' => [
                'amount' => $response->getReceipt()->getMoney()->getAmount(),
                'currency' => $response->getReceipt()->getMoney()->getCurrency(),
            ],
            'fee' => [
                'amount' => $response->getReceipt()->getFee()->getAmount(),
                'currency' => $response->getReceipt()->getFee()->getCurrency(),
            ],
            'created_at' => $response->getReceipt()->getCreatedAt()->toDateTime(),
        ];
    }
}
