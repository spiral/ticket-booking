<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefaultBa2facffb7fc94f5f0cc063a5a202d86 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('auditoriums')
            ->addColumn('id', 'bigPrimary', ['nullable' => false, 'default' => null])
            ->addColumn('name', 'string', ['nullable' => false, 'default' => null, 'size' => 255])
            ->setPrimaryKeys(['id'])
            ->create();


        $this->table('auditorium_seats')
            ->addColumn('id', 'bigPrimary', ['nullable' => false, 'default' => null])
            ->addColumn('row', 'integer', ['nullable' => false, 'default' => null])
            ->addColumn('number', 'integer', ['nullable' => false, 'default' => null])
            ->addColumn('auditorium_id', 'bigInteger', ['nullable' => false, 'default' => null])
            ->addIndex(['auditorium_id'],
                ['name' => 'auditorium_seats_index_auditorium_id_62bdc039031de', 'unique' => false])
            ->addIndex(['row', 'number', 'auditorium_id'],
                ['name' => '8df0fc18c6fcec7b6d2ca48028d53f7d', 'unique' => true])
            ->addForeignKey(['auditorium_id'], 'auditoriums', ['id'], [
                'name' => 'auditorium_seats_foreign_auditorium_id_62bdc039031eb',
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->setPrimaryKeys(['id'])
            ->create();

        $this->table('movies')
            ->addColumn('id', 'bigPrimary', ['nullable' => false, 'default' => null])
            ->addColumn('title', 'string', ['nullable' => false, 'default' => null, 'size' => 255])
            ->addColumn('description', 'text', ['nullable' => false, 'default' => null])
            ->addColumn('duration', 'integer', ['nullable' => false, 'default' => null])
            ->addIndex(['title'], ['name' => 'movies_index_title_62bdc039033f2', 'unique' => true])
            ->setPrimaryKeys(['id'])
            ->create();
        $this->table('reservation_types')
            ->addColumn('id', 'bigPrimary', ['nullable' => false, 'default' => null])
            ->addColumn('name', 'string', ['nullable' => false, 'default' => null, 'size' => 255])
            ->addIndex(['name'], ['name' => 'reservation_types_index_name_62bdc0390340b', 'unique' => true])
            ->setPrimaryKeys(['id'])
            ->create();
        $this->table('screenings')
            ->addColumn('id', 'bigPrimary', ['nullable' => false, 'default' => null])
            ->addColumn('starts_at', 'datetime', ['nullable' => false, 'default' => null])
            ->addColumn('movie_id', 'bigInteger', ['nullable' => false, 'default' => null])
            ->addColumn('auditorium_id', 'bigInteger', ['nullable' => false, 'default' => null])
            ->addIndex(['movie_id'], ['name' => 'screenings_index_movie_id_62bdc0390330f', 'unique' => false])
            ->addIndex(['auditorium_id'], ['name' => 'screenings_index_auditorium_id_62bdc0390332e', 'unique' => false])
            ->addIndex(['starts_at', 'movie_id', 'auditorium_id'],
                ['name' => 'c5c72dc9ca7b4029937edeb3a416139c', 'unique' => true])
            ->addForeignKey(['movie_id'], 'movies', ['id'], [
                'name' => 'screenings_foreign_movie_id_62bdc03903314',
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->addForeignKey(['auditorium_id'], 'auditoriums', ['id'], [
                'name' => 'screenings_foreign_auditorium_id_62bdc03903334',
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->setPrimaryKeys(['id'])
            ->create();

        $this->table('reservations')
            ->addColumn('created_at', 'datetime', ['nullable' => false, 'default' => null])
            ->addColumn('expires_at', 'datetime', ['nullable' => false, 'default' => null])
            ->addColumn('paid_at', 'datetime', ['nullable' => true, 'default' => null])
            ->addColumn('canceled_at', 'datetime', ['nullable' => true, 'default' => null])
            ->addColumn('uuid', 'string', ['nullable' => false, 'default' => null, 'size' => 255])
            ->addColumn('transaction_id', 'string', ['nullable' => true, 'default' => null, 'size' => 255])
            ->addColumn('screening_id', 'bigInteger', ['nullable' => false, 'default' => null])
            ->addColumn('type_id', 'bigInteger', ['nullable' => false, 'default' => null])
            ->addColumn('user_id', 'integer', ['nullable' => false, 'default' => null])
            ->addIndex(['screening_id'], ['name' => 'reservations_index_screening_id_62bdc039032cf', 'unique' => false])
            ->addIndex(['type_id'], ['name' => 'reservations_index_type_id_62bdc039032ee', 'unique' => false])
            ->addForeignKey(['screening_id'], 'screenings', ['id'], [
                'name' => 'reservations_foreign_screening_id_62bdc039032d4',
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->addForeignKey(['type_id'], 'reservation_types', ['id'], [
                'name' => 'reservations_foreign_type_id_62bdc039032f4',
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->setPrimaryKeys(['uuid'])
            ->create();

        $this->table('auditorium_reserved_seats')
            ->addColumn('id', 'bigPrimary', ['nullable' => false, 'default' => null])
            ->addColumn('seat_id', 'bigInteger', ['nullable' => false, 'default' => null])
            ->addColumn('reservation_uuid', 'string', ['nullable' => false, 'default' => null, 'size' => 255])
            ->addIndex(['seat_id'],
                ['name' => 'auditorium_reserved_seats_index_seat_id_62bdc0390326f', 'unique' => false])
            ->addIndex(['reservation_uuid'], ['name' => '6f415526529da037cd110edfee5c53be', 'unique' => false])
            ->addIndex(['seat_id', 'reservation_uuid'], ['name' => '77f01ca761e24c7dc4ced8fc5254d4e2', 'unique' => true]
            )
            ->addForeignKey(['seat_id'], 'auditorium_seats', ['id'], [
                'name' => 'auditorium_reserved_seats_foreign_seat_id_62bdc03903275',
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->addForeignKey(['reservation_uuid'], 'reservations', ['uuid'], [
                'name' => 'e8cd9db0580c401e6bf5d174f39c35ff',
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->setPrimaryKeys(['id'])
            ->create();

        $this->table('auth_tokens')
            ->addColumn('id', 'string', ['nullable' => false, 'default' => null, 'size' => 64])
            ->addColumn('hashed_value', 'string', ['nullable' => false, 'default' => null, 'size' => 128])
            ->addColumn('created_at', 'datetime', ['nullable' => false, 'default' => null])
            ->addColumn('expires_at', 'datetime', ['nullable' => true, 'default' => null])
            ->addColumn('payload', 'binary', ['nullable' => false, 'default' => null])
            ->setPrimaryKeys(['id'])
            ->create();
    }

    public function down(): void
    {
        $this->table('auth_tokens')->drop();
        $this->table('screenings')->drop();
        $this->table('reservation_types')->drop();
        $this->table('movies')->drop();
        $this->table('reservations')->drop();
        $this->table('auditorium_reserved_seats')->drop();
        $this->table('auditorium_seats')->drop();
        $this->table('auditoriums')->drop();
    }
}
