<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roles
        Role::firstOrCreate(
            ['name' => 'superadmin'],
            ['description' => 'Administrator dan creator dengan akses penuh']
        );

        Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Administrator dan creator dengan akses terbatas']
        );

        Role::firstOrCreate(
            ['name' => 'author'],
            ['description' => 'Creator dengan akses terbatas']
        );

        Role::firstOrCreate(
            ['name' => 'user'],
            ['description' => 'Pengguna reguler']
        );

        // Users
        User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'NIK' => 'LTG000000',
                'name' => 'Super Admin',
                'role' => 'superadmin',
                'password' => bcrypt('superadmin1234'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'NIK' => 'LTG000010',
                'name' => 'Admin',
                'role' => 'admin',
                'password' => bcrypt('admin1234'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'author@gmail.com'],
            [
                'NIK' => 'LTG0000010',
                'name' => 'Author',
                'role' => 'author',
                'password' => bcrypt('author1234'),
            ]
        );
    }
}