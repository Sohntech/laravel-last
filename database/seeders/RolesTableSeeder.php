<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = ['Admin', 'Manager', 'CM', 'Coach'];

        // Effacer les rôles existants
        DB::table('roles')->truncate();

        // Insérer les nouveaux rôles
        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'name' => $role,
            ]);
        }
    }
}
