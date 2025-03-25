<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'logo'=> 'default.png',
            'name' => 'Admin Emenu',
            'username' => 'admin',
            'email' => 'admin@emenu.com',
            'password' => bcrypt('123'),
            'role' => 'admin',
        ]);

    }
}
