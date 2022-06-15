<?php

namespace Database\Factories;
use App\Models\PreviousDancePartner;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PreviousDancePartner>
 */
class PreviousDancePartnerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PreviousDancePartner::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $first_id = User::inRandomOrder()->first()->toArray()['id'];
        $second_id = User::whereNot('id', $first_id)->inRandomOrder()->first()->toArray()['id'];

        return [
            'user' => $first_id,
            'partner' => $second_id,
            'event_id' => Event::where('date', '>=', Carbon::now('Europe/Vienna'))->inRandomOrder()->first()->toArray()['id'],
        ];
    }
}
