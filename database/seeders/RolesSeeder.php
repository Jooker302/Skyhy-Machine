<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Engineer Supervisor', 'slug' => 'engineer_supervisor'],
            ['name' => 'Engineer User', 'slug' => 'engineer_user'],
            ['name' => 'Basic', 'slug' => 'basic'],
            ['name' => 'Client User', 'slug' => 'client_user'],
            ['name' => 'Client Technical', 'slug' => 'client_technical'],
            ['name' => 'Client Supervisor', 'slug' => 'client_supervisor'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insert($role);
        }
    }
}

