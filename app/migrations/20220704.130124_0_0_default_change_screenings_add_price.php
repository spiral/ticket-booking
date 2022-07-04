<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefault69354a2ce690f3153d1af3141a2d3dc5 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('screenings')
            ->addColumn('price', 'integer', ['nullable' => false, 'default' => null, 'size' => 255])
            ->update();
    }

    public function down(): void
    {
        $this->table('screenings')
            ->dropColumn('price')
            ->update();
    }
}
