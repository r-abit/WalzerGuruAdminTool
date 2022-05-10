<?php

namespace Database\Factories;
use App\Models\Organizer;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Orgnizer>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'organizer_id' => rand(1,Organizer::count()),
            'name' => $this->faker->company() . ' ' . $this->faker->companySuffix(),
            'participants' => rand(100, 400) * 2,
            'date' => date('Y-m-d H:i:s', rand(strtotime("-1 Months"), strtotime("+6 Months"))),
            'dresscode' => uniqid(),
            'street' => $this->faker->streetAddress(),
            'zip' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'description' => $this->faker->realTextBetween(260, 1000, 2),
        ];
    }
}
