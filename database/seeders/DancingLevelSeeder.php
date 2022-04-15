<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DancingLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dancing_level')->insert([
            ['level' => 'Profitänzer'],
            ['level' => 'Turniertänzer'],
            ['level' => 'Breitensport'],
            ['level' => 'Tanzkreis'],
            ['level' => 'Mittelstufe (Salsa)'],
            ['level' => 'weit Fortgeschritten'],
            ['level' => 'Fortgeschritten'],
            ['level' => 'Einsteiger'],
        ]);
    }
}
