<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin creation
        // User::factory()->create([
        //     'name' => 'Admin',
        //     'email' => 'admin@test.com',
        //     'password' => Hash::make('test')
        // ]);

        /* Room + 1 reservation
        Room::factory(10)->create();
        Reservation::factory(1)->create();
        */
    }
}
