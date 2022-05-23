<?php

namespace Database\Seeders;

use App\Models\EventParticipation;
use Illuminate\Database\Seeder;
use App\Models\LikedUsers;
use App\Models\Organizer;
use App\Models\Event;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Organizer::factory(15)->create();
        Event::factory(50)->create();

        $this->call([
            DancingLevelSeeder::class,
            UserSeeder::class,
        ]);

        User::factory(297)->create();

        /**
         * IMPORTANT
         * This can only run if User::factory has been run before AND
         * factory count needs to be less than the 2nd parameter
         */
        LikedUsers::factory(100)->create();

        EventParticipation::factory(1000)->create();
    }
}
