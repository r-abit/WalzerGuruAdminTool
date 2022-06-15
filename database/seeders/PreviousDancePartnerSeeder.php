<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\PreviousDancePartner;
use Illuminate\Database\Seeder;

class PreviousDancePartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PreviousDancePartner::factory()->count(200)->create();
    }
}
