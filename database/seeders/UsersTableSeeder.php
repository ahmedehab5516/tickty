<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use App\Models\Cinema; // To associate with cinemas

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Fetch role IDs indexed by role_title
        $roles = Role::pluck('id', 'role_title');
        
        // Ensure all required roles exist
        $requiredRoles = ['user', 'admin', 'staff', 'superadmin'];
        foreach ($requiredRoles as $role) {
            if (!isset($roles[$role])) {
                $this->command->error("Missing role: {$role}. Please seed the roles table first.");
                return;
            }
        }

        // Fetch companies and cinemas
        $company = Company::first(); // Assuming only one company for simplicity
        $cinema = Cinema::first(); // Assuming only one cinema for simplicity

        // Users to seed
        $users = [
            [
                'name' => 'Nancy',
                'email' => 'nancy@gmail.com',
                'phone' => '01147855478',
                'password' => '74108520',
                'role' => 'user',
                'salary' => 4000, // Example salary
                'company_id' => null,
                'cinema_id' =>  null, // Assign the cinema_id
            ],
            [
                'name' => 'Ahmed',
                'email' => 'ahmed@gmail.com',
                'phone' => '01145822367',
                'password' => '74108520',
                'role' => 'admin',
                'salary' => 5000, // Example salary
                'company_id' => $company ? $company->id : null, // Assign the company_id
                'cinema_id' => $cinema ? $cinema->id : null, // Assign the cinema_id
            ],
            [
                'name' => 'Mohsen',
                'email' => 'mohsen@gmail.com',
                'phone' => '01145822369',
                'password' => '74108520',
                'role' => 'staff',
                'salary' => 3000, // Example salary
                'company_id' => $company ? $company->id : null, // Assign the company_id
                'cinema_id' => $cinema ? $cinema->id : null, // Assign the cinema_id
            ],
            [
                'name' => 'Asala',
                'email' => 'asala@gmail.com',
                'phone' => '01147855236',
                'password' => '74108520',
                'role' => 'superadmin',
                'salary' => 6000, // Example salary
                'company_id' => $company ? $company->id : null, // Assign the company_id
                'cinema_id' => $cinema ? $cinema->id : null, // Assign the cinema_id
            ],

              [
                'name' => 'Farah',
                'email' => 'farah@gmail.com',
                'phone' => '01147855236',
                'password' => '74108520',
                'role' => 'superadmin',
                'salary' => 6000, // Example salary
                'company_id' => 2,
                'cinema_id' => null
            ],

              [
                'name' => 'Farah',
                'email' => 'farah@gmail.com',
                'phone' => '01147855236',
                'password' => '74108520',
                'role' => 'superadmin',
                'salary' => 6000, // Example salary
                'company_id' => 3,
                'cinema_id' => null
            ],
        ];

        // Loop through the users and create them
        foreach ($users as $data) {
            User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'password' => Hash::make($data['password']),
                    'role_id' => $roles[$data['role']],
                    'salary' => $data['salary'],  // Add salary
                    'company_id' => $data['company_id'], // Add company_id
                    'cinema_id' => $data['cinema_id'], // Add cinema_id
                ]
            );
        }

        $this->command->info('Users seeded successfully!');
    }
}
