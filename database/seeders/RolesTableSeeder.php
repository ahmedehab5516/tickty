<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['role_title' => 'user'],
            ['role_title' => 'admin'],
            ['role_title' => 'superadmin'],
        ]);
    }
}
