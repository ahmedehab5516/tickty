<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Fetch role IDs
        $roles = DB::table('roles')->pluck('id', 'role_title');

        DB::table('users')->insert([
            [
                'name' => 'nancy',
                'email' => 'nancy@gmail.com',
                'password' => Hash::make('74108520'),
                'role_id' => $roles['user'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ahmed',
                'email' => 'ahmed@gmail.com',
                // 'hashed_password' => Hash::make('password'),
                 'password' => Hash::make('74108520'),
                'role_id' => $roles['admin'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'asala',
                'email' => 'asala@gmail.com',
                // 'hashed_password' => Hash::make('password'),
               'password' => Hash::make('74108520'),

                'role_id' => $roles['superAdmin'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
