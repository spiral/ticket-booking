<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefaultE117449ffbf6ecf715fb6691cb8ae822 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('auditorium_reserved_seats')
            ->addColumn('screening_id', 'bigInteger', ['nullable' => false, 'default' => null])
            ->addIndex(['screening_id'], ['name' => '29297fe44925eaeffd9dff264e57d710', 'unique' => false])
            ->addForeignKey(['screening_id'], 'screenings', ['id'], [
                'name' => '003e0c56d478e7cb41fa4154964c8422',
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->update();
    }

    public function down(): void
    {
        $this->table('auditorium_reserved_seats')
            ->dropForeignKey(['screening_id'])
            ->dropIndex(['screening_id'])
            ->dropColumn('screening_id')
            ->update();
    }
}
