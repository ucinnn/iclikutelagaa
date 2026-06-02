<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Role::factory()->create([
            'name' => 'superadmin',
            'description' => 'Administrator dan creator dengan akses penuh',
        ]);
        Role::factory()->create([
            'name' => 'admin',
            'description' => 'Administrator dan creator dengan akses terbatas',
        ]);
        Role::factory()->create([
            'name' => 'author',
            'description' => 'Creator dengan akses terbatas',
        ]);
        Role::factory()->create([
            'name' => 'user',
            'description' => 'Pengguna reguler',
        ]);

        User::factory()->create([
            'NIK' => 'LTG000000',
            'name' => 'Super Admin',
            'role' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('superadmin1234'),
        ]);

        User::factory()->create([
            'NIK' => 'LTG000010',
            'name' => 'Admin',
            'role' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin1234'),
        ]);

        User::factory()->create([
            'NIK' => 'LTG0000010',
            'name' => 'Author',
            'role' => 'author',
            'email' => 'author@gmail.com',
            'password' => bcrypt('author1234'),
        ]);
    }
}
