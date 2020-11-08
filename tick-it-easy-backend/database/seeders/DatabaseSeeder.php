<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            VehiclesTableSeeder::class,
            RoutesTableSeeder::class,
            TicketsTableSeeder::class,
            UsersTableSeeder::class
        ]);
    }
}
