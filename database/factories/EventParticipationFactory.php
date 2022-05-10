<?php

namespace Database\Factories;
use App\Models\Event;
use App\Models\Organizer;
use App\Models\User;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Orgnizer>
 */
class EventParticipationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'event_id' => rand(1, Event::count()),
            'user_id' => rand(1, User::count()),
            'age' => rand(0,1),
            'height' => rand(0,1),
            'level' => rand(0,1),
        ];
    }
}
