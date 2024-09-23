<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        \App\Models\User::factory(1)->create(); // Crée 10 utilisateurs
    }
}
