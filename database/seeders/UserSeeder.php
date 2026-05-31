<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Superadmin',
            'email' => 'superadmin@arj.com',
            'team_name' => 'Ayah Racing Jaya',
            'phone_number' => null,
            'password' => Hash::make('ayahracingjaya'),
            'verified_data' => true,
            'role' => 'superadmin',
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@arj.com',
            'team_name' => 'Ayah Racing Jaya',
            'phone_number' => null,
            'password' => Hash::make('ayahracingjaya'),
            'verified_data' => true,
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@arj.com',
            'team_name' => 'Ayah Racing Jaya',
            'phone_number' => null,
            'password' => Hash::make('ayahracingjaya'),
            'verified_data' => true,    
            'role' => 'user',
        ]);
    }
}
