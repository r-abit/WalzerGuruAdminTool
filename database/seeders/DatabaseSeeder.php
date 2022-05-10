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
        \App\Models\Organizer::factory(15)->create();
        \App\Models\Event::factory(50)->create();

        $this->call([
            DancingLevelSeeder::class,
            UserSeeder::class,
        ]);

        \App\Models\User::factory(297)->create();
        \App\Models\EventParticipation::factory(1000)->create();
    }
}
