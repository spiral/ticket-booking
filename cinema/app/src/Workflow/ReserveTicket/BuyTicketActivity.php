<?php

declare(strict_types=1);

namespace App\Workflow\ReserveTicket;

use App\Application\Command\SendEmailCommand;
use App\Application\Query\GetUserQuery;
use App\Entity\Auditorium\ReservedSeat;
use App\Event\TicketBought;
use App\Repository\ReservationRepositoryInterface;
use Cycle\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Spiral\Cqrs\CommandBusInterface;
use Spiral\Cqrs\QueryBusInterface;

class BuyTicketActivity implements BuyTicketActivityInterface
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservations,
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus
    ) {
    }

    public function pay(string $reservationId, string $transactionId): array
    {
        $reservation = $this->reservations->getByPK($reservationId);

        $reservation->markAsPaid(
            $transactionId
        );

        $this->entityManager->persist($reservation);
        $this->entityManager->run();
        $this->entityManager->clean();

        $this->eventDispatcher->dispatch(new TicketBought($reservation));

        return \array_map(
            fn(ReservedSeat $seat) => $seat->getSeat()->getId(),
            $reservation->getSeats()
        );
    }

    #[ActivityMethod]
    public function sendTicketsByMail(string $reservationId)
    {
        $reservation = $this->reservations->getByPK($reservationId);

        $user = $this->queryBus->ask(new GetUserQuery($reservation->getUserId()));

        $this->commandBus->dispatch(new SendEmailCommand(
            template: 'mail.dark.php',
            email: $user->getEmail(),
            data: [
                'email' => $user->getEmail(),
                'seats' => \implode(
                    ', ',
                    \array_map(
                        fn(ReservedSeat $seat): string => \sprintf(
                            'Seat row %d, num %d',
                            $seat->getSeat()->getRow(),
                            $seat->getSeat()->getNumber()
                        ),
                        $reservation->getSeats()
                    )
                ),
                'auditorium' => $reservation->getScreening()->getAuditorium()->getName(),
                'startsAt' => $reservation->getScreening()->getStartsAt()->format('d f Y H:i'),
            ]
        ));
    }
}
