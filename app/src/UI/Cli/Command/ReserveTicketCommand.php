<?php

declare(strict_types=1);

namespace App\UI\Cli\Command;

use App\Entity\Auditorium\ReservedSeat;
use App\Entity\Auditorium\Seat;
use App\Repository\ScreeningRepositoryInterface;
use App\Workflow\ReserveTicket\ReserveTicketActivity;
use Spiral\Console\Command;
use Spiral\Cqrs\CommandBusInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReserveTicketCommand extends Command
{
    protected const SIGNATURE = 'reserve:ticket';
    protected SymfonyStyle $io;

    public function __invoke(
        ScreeningRepositoryInterface $screenings,
        CommandBusInterface $bus,
        ReserveTicketActivity $activity
    ) {
        $this->io = new SymfonyStyle($this->input, $this->output);

        $activeScreenings = [];

        foreach ($screenings->findAllActive() as $screening) {
            $activeScreenings[$screening->getId()] = [
                $screening->getId(),
                $screening->getMovie()->getTitle(),
                $screening->getStartsAt()->format('d.m.Y H:i'),
                $screening->getMovie()->getDuration().' min',
                $screening->getAuditorium()->getTotalSeats(),
                $screening->getAuditorium()->getName(),
                $screening->getPrice() . '$',
            ];
        }

        $this->io->table(['ID', 'Movie', 'Starts at', 'Duration', 'Seats', 'Auditorium', 'Price'], $activeScreenings);

        do {
            $screeningId = (int)$this->io->ask('Select movie ID');
        } while (! isset($activeScreenings[$screeningId]));

        $screening = $screenings->getByPK($screeningId);
        $seats = \array_map(fn(Seat $seat) => $seat->getId(), $this->renderSeats($screening));

        $command = new \App\Application\Command\ReserveTicketCommand(
            $screeningId,
            1,
            $seats
        );

        try {
            $bus->dispatch($command);

            $this->io->info(\sprintf(
                'Reservation [%s] started. Use it  for purchasing the tickets...',
                $command->reservationId
            ));
        } catch (\Throwable $e) {
            $this->io->warning($e->getMessage());
        }

//        $activity->reserve(
//            Uuid::uuid4()->toString(),
//            $command->screeningId,
//            $command->reservationTypeId,
//            $command->seatIds
//        );
    }

    /**
     * @param \App\Entity\Screening $screening
     * @return Seat[]
     */
    private function renderSeats(\App\Entity\Screening $screening): array
    {
        $reservedSeats = \array_map(
            fn(ReservedSeat $seat) => $seat->getSeat()->getId(),
            $screening->getReservedSeats()
        );

        $row = 0;

        $seats = $screening->getAuditorium()->getSeats();
        foreach ($screening->getAuditorium()->getSeats() as $i => $seat) {
            if ($row < $seat->getRow()) {
                $this->newLine(2);
                $row = $seat->getRow();
                $this->write(' ');
                $this->write(\str_pad((string)$seat->getRow(), 5, ' '));
                $this->write('|');
            }

            if (\in_array($seat->getId(), $reservedSeats)) {
                $this->write(\sprintf('<error>%s</error>', \str_pad('X', 6, 'X', STR_PAD_BOTH)));
            } else {
                $this->write(\sprintf('<info>%s</info>', \str_pad((string)$i, 6, ' ', STR_PAD_BOTH)));
            }

            $this->write('|');
        }

        $this->newLine(3);

        do {
            $selectedSeats = \explode(',', $this->io->ask('Select seats'));
        } while ($selectedSeats === []);

        return \array_map(fn(int $i) => $seats[$i], $selectedSeats);
    }
}
