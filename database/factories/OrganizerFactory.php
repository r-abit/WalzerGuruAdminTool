<?php

namespace Database\Factories;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Orgnizer>
 */
class OrganizerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->email(),
            'website' => Str::slug($this->faker->company(), '-') . '.com',
            'uid_number' => uniqid(),
            'street' => $this->faker->streetAddress(),
            'zip' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'phone' => $this->faker->e164PhoneNumber(),
            'description' => $this->faker->realTextBetween(260, 1000, 2),
        ];
    }
}
