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
            DancingLevelSeeder::class,
        ]);

        \App\Models\Organizer::factory(15)->create();
        \App\Models\Event::factory(50)->create();
    }
}
