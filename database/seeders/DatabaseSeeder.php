public function run(): void
{
    Role::create([
        'name' => 'superadmin',
        'description' => 'Administrator dan creator dengan akses penuh',
    ]);

    Role::create([
        'name' => 'admin',
        'description' => 'Administrator dan creator dengan akses terbatas',
    ]);

    Role::create([
        'name' => 'author',
        'description' => 'Creator dengan akses terbatas',
    ]);

    Role::create([
        'name' => 'user',
        'description' => 'Pengguna reguler',
    ]);

    User::create([
        'NIK' => 'LTG000000',
        'name' => 'Super Admin',
        'role' => 'superadmin',
        'email' => 'superadmin@gmail.com',
        'password' => bcrypt('superadmin1234'),
    ]);

    User::create([
        'NIK' => 'LTG000010',
        'name' => 'Admin',
        'role' => 'admin',
        'email' => 'admin@gmail.com',
        'password' => bcrypt('admin1234'),
    ]);

    User::create([
        'NIK' => 'LTG0000010',
        'name' => 'Author',
        'role' => 'author',
        'email' => 'author@gmail.com',
        'password' => bcrypt('author1234'),
    ]);
}