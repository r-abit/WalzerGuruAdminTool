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
        $properties = ["level", "height", "age"];
        shuffle($properties);
        return [
            'event_id' => rand(1, Event::count()),
            'user_id' => rand(1, User::count()),
            'priorities' => json_encode($properties),
            'previous_dancer' => rand(0,1),
        ];
    }
}
