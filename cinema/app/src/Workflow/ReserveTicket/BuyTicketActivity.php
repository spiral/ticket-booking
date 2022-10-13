<?php

declare(strict_types=1);

namespace App\Workflow\ReserveTicket;

use App\Entity\Auditorium\ReservedSeat;
use App\Event\TicketBought;
use App\Repository\ReservationRepositoryInterface;
use App\Services\Payment\PaymentService;
use Cycle\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Spiral\Mailer\MailerInterface;
use Spiral\Mailer\Message;
use Spiral\Shared\GRPC\RequestContext;
use Spiral\Shared\Services\Payment\v1\DTO\ChargeRequest;
use Spiral\Shared\Services\Users\v1\DTO\GetRequest;
use Spiral\Shared\Services\Users\v1\UsersServiceInterface;
use Temporal\Activity\ActivityMethod;

class BuyTicketActivity implements BuyTicketActivityInterface
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservations,
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly UsersServiceInterface $usersService,
        private readonly MailerInterface $mailer
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
        $response = $this->usersService->Get(
            new RequestContext(),
            new GetRequest([
                'id' => $reservation->getUserId(),
            ])
        );

        $this->mailer->send(
            new Message(
                subject: 'mail.dark.php',
                to: $response->getUser()->getEmail(),
                data: [
                    'email' => $response->getUser()->getEmail(),
                    'seats' => \implode(', ', \array_map(
                        fn(ReservedSeat $seat) => \sprintf(
                            'Seat row %d, num %d',
                            $seat->getSeat()->getRow(),
                            $seat->getSeat()->getNumber()
                        ),
                        $reservation->getSeats()
                    )),
                    'auditorium' => $reservation->getScreening()->getAuditorium()->getName(),
                    'startsAt' => $reservation->getScreening()->getStartsAt()->format('d f Y H:i'),
                ]
            )
        );
    }
}
