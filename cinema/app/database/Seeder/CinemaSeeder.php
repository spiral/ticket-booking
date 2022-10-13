<?php

declare(strict_types=1);

namespace Database\Seeder;

use App\Entity\Auditorium;
use App\Entity\Movie;
use App\ValueObject\Duration;
use Carbon\Carbon;
use Cycle\Database\DatabaseInterface;
use Database\Factory\AuditoriumFactory;
use Database\Factory\MovieFactory;
use Database\Factory\ReservationTypeFactory;
use Database\Factory\ScreeningFactory;
use Database\Factory\SeatFactory;
use Spiral\Core\ContainerScope;
use Spiral\DatabaseSeeder\Seeder\AbstractSeeder;

class CinemaSeeder extends AbstractSeeder
{
    public function run(): \Generator
    {
        $this->truncateTables();

        /** @var Movie $movie1 */
        yield $movie1 = MovieFactory::new([
            'title' => 'Elvis',
            'duration' => new Duration(159),
            'description' => <<<TEXT
ELVIS is Oscar-nominated filmmaker Baz Luhrmann's musical drama about the life and music of Elvis Presley, seen through
the prism of his complicated relationship with his enigmatic manager, Colonel Tom Parker. The story delves into the
complex dynamic between Presley and Parker spanning over 20 years, from Presley's rise to fame to his unprecedented
stardom, against the backdrop of the evolving cultural landscape and loss of innocence in America. Central to that
journey is one of the most significant and influential people in Elvis's life, Priscilla Presley.
TEXT
            ,
        ])->createOne();

        /** @var Movie $movie2 */
        yield $movie2 = MovieFactory::new([
            'title' => 'Lightyear',
            'duration' => new Duration(105),
            'description' => <<<TEXT
A sci-fi action adventure and the definitive origin story of Buzz Lightyear (voice of Chris Evans), the hero who
inspired the toy, “Lightyear” follows the legendary Space Ranger on an intergalactic adventure alongside a group of
ambitious recruits (voices of Keke Palmer, Dale Soules and Taika Waititi), and his robot companion Sox
(voice of Peter Sohn).
TEXT
            ,
        ])->createOne();

        /** @var Movie $movie3 */
        yield $movie3 = MovieFactory::new([
            'title' => 'Top Gun: Maverick',
            'duration' => new Duration(131),
            'description' => <<<TEXT
After more than thirty years of service as one of the Navy's top aviators, Pete 'Maverick' Mitchell (Tom Cruise) is
 where he belongs, pushing the envelope as a courageous test pilot and dodging the advancement in rank that would ground
 him. When he finds himself training a detachment of Top Gun graduates for a specialized mission the likes of which no
 living pilot has ever seen, Maverick encounters Lt. Bradley Bradshaw (Miles Teller), call sign: 'Rooster,' the son of
 Maverick's late friend and Radar Intercept Officer Lt. Nick Bradshaw, aka 'Goose'. Facing an uncertain future and
 confronting the ghosts of his past, Maverick is drawn into a confrontation with his own deepest fears, culminating in
 a mission that demands the ultimate sacrifice from those who will be chosen to fly it.
TEXT
            ,
        ])->createOne();

        /** @var Movie $movie4 */
        yield $movie4 = MovieFactory::new([
            'title' => 'Jurassic World Dominion',
            'duration' => new Duration(147),
            'description' => <<<TEXT
This summer, experience the epic conclusion to the Jurassic era as two generations unite for the first time. Chris
Pratt and Bryce Dallas Howard are joined by Oscar®-winner Laura Dern, Jeff Goldblum and Sam Neill in Jurassic World
Dominion, a bold, timely and breathtaking new adventure that spans the globe. From Jurassic World architect and
director Colin Trevorrow, Dominion takes place four years after Isla Nublar has been destroyed. Dinosaurs now
live—and hunt—alongside humans all over the world. This fragile balance will reshape the future and determine, once
and for all, whether human beings are to remain the apex predators on a planet they now share with history’s most
fearsome creatures. Jurassic World Dominion, from Universal Pictures and Amblin Entertainment, propels the more than
$5 billion franchise into daring, uncharted territory, featuring never-seen dinosaurs, breakneck action and
astonishing new visual effects.
TEXT
            ,
        ])->createOne();

        /** @var Auditorium $auditorium1 */
        yield $auditorium1 = AuditoriumFactory::new([
            'name' => 'big',
        ])->createOne();

        /** @var Auditorium $auditorium2 */
        yield $auditorium2 = AuditoriumFactory::new([
            'name' => 'medium',
        ])->createOne();

        /** @var Auditorium $auditorium3 */
        yield $auditorium3 = AuditoriumFactory::new([
            'name' => 'small',
        ])->createOne();

        /** @var Auditorium $auditorium4 */
        yield $auditorium4 = AuditoriumFactory::new([
            'name' => 'imax',
        ])->createOne();

        yield from $this->createSeats($auditorium3, rows: 5, seatsInRow: 10);
        yield from $this->createSeats($auditorium2, rows: 7, seatsInRow: 15);
        yield from $this->createSeats($auditorium1, rows: 10, seatsInRow: 20);
        yield from $this->createSeats($auditorium4, rows: 13, seatsInRow: 25);

        yield from $this->createSchedule(
            movie: $movie1,
            auditorium: $auditorium1,
            startAt: Carbon::now()->setMinutes(0)->setSeconds(0),
            repeats: 5
        );

        yield from $this->createSchedule(
            movie: $movie2,
            auditorium: $auditorium2,
            startAt: Carbon::now()->setMinutes(0)->setSeconds(0),
            repeats: 6
        );

        yield from $this->createSchedule(
            movie: $movie3,
            auditorium: $auditorium3,
            startAt: Carbon::now()->setMinutes(0)->setSeconds(0),
            repeats: 6
        );

        yield from $this->createSchedule(
            movie: $movie4,
            auditorium: $auditorium4,
            startAt: Carbon::now()->setMinutes(0)->setSeconds(0),
            repeats: 6
        );

        yield ReservationTypeFactory::new([
            'name' => 'online',
        ])->createOne();
    }

    private function createSchedule(
        Movie $movie,
        Auditorium $auditorium,
        \DateTimeInterface $startAt,
        int $repeats = 5
    ): \Generator {
        for ($i = 1; $i <= $repeats; $i++) {
            yield ScreeningFactory::new([
                'movie' => $movie,
                'auditorium' => $auditorium,
                'startsAt' => $startAt,
            ])->createOne();

            $startAt = $startAt->addMinutes($movie->getDuration())->addMinutes(20);
        }
    }

    private function createSeats(Auditorium $auditorium, int $rows, int $seatsInRow): \Generator
    {
        for ($row = 1; $row <= $rows; $row++) {
            for ($num = 1; $num <= $seatsInRow; $num++) {
                yield SeatFactory::new([
                    'row' => $row,
                    'number' => $num,
                    'auditorium' => $auditorium,
                ])->createOne();
            }
        }
    }

    public function truncateTables(): void
    {
        $container = ContainerScope::getContainer();

        $db = $container->get(DatabaseInterface::class);
        \assert($db instanceof DatabaseInterface);

        foreach ($db->getTables() as $table) {
            if ($table->getName() === 'public.migrations') {
                continue;
            }
            $db->query(\sprintf('TRUNCATE TABLE %s RESTART IDENTITY CASCADE', $table->getName()));
        }
    }
}
