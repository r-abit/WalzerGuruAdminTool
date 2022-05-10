<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Organizer;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'username' => 'org_',
                'role' => 'organizer',
                'email' => 'org@live.com',
                'firstname' => 'Org',
                'lastname' => 'Organizer',
                'organizer_id' => (rand(1, Organizer::count())),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            ]
        ]);
        User::insert([
            [
                'username' => 'admin_',
                'role' => 'admin',
                'email' => 'admin@live.com',
                'firstname' => 'Root',
                'lastname' => 'Admin',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            ],
            [
                'username' => 'user_',
                'role' => 'user',
                'email' => 'user@live.com',
                'firstname' => 'User',
                'lastname' => 'Name',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            ],
        ]);
    }
}
