<?php

declare(strict_types=1);

namespace App\Console;

use App\Entity\Auditorium\ReservedSeat;
use App\Entity\Screening;
use App\Repository\ScreeningRepositoryInterface;
use Spiral\Console\Command;

class ShowReservedSeatsCommand extends Command
{
    protected const SIGNATURE = 'seats';

    public function __invoke(ScreeningRepositoryInterface $repository)
    {
        /** @var Screening[] $screenings */
        $screenings = $repository->findAll();

        foreach ($screenings as $screening) {
            $this->info(\sprintf('Screening #%s', $screening->getId()));
            $this->info($screening->getMovie()->getTitle());
            $this->info(\sprintf('Auditorium: %s', $screening->getAuditorium()->getName()));

            $this->info('Seats: ');
            $reservedSeats = \array_map(
                fn(ReservedSeat $seat) => $seat->getSeat()->getId(),
                $screening->getReservedSeats()
            );

            $row = 0;
            foreach ($screening->getAuditorium()->getSeats() as $seat) {
                if ($row < $seat->getRow()) {
                    $this->newLine();
                    $row = $seat->getRow();
                }

                if (\in_array($seat->getId(), $reservedSeats)) {
                    $this->write('X');
                } else {
                    $this->write('.');
                }
            }

            $this->newLine(3);
        }
    }
}
