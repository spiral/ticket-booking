<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefault9b488c61de5dc807cb2878d38461aa4d extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('users')
            ->addColumn('id', 'primary', ['nullable' => false, 'default' => null])
            ->addColumn('email', 'string', ['nullable' => false, 'default' => null, 'size' => 255])
            ->addColumn('roles', 'json', ['nullable' => false, 'default' => null])
            ->addColumn('password', 'string', ['nullable' => false, 'default' => null, 'size' => 255])
            ->addIndex(['email'], ['name' => 'users_index_email_62c5a0bd88e0b', 'unique' => true])
            ->setPrimaryKeys(['id'])
            ->create();
    }

    public function down(): void
    {
        $this->table('users')->drop();
    }
}
