<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefaultB437cc8f9ac6097213eff7c74570ba48 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('reservations')
        ->addColumn('user_id', 'integer', ['nullable' => false, 'default' => null])
        ->addIndex(['user_id'], ['name' => 'reservations_index_user_id_62c69f1a42407', 'unique' => false])
        ->addForeignKey(['user_id'], 'users', ['id'], [
            'name' => 'reservations_foreign_user_id_62c69f1a42419',
            'delete' => 'CASCADE',
            'update' => 'CASCADE',
        ])
        ->update();
    }

    public function down(): void
    {
        $this->table('reservations')
        ->dropForeignKey(['user_id'])
        ->dropIndex(['user_id'])
        ->dropColumn('user_id')
        ->update();
    }
}
