<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Super Admin',
                'email'    => 'admin@sinaralam.id',
                'telepon'  => '08123456789',
                'role'     => 'admin',
                'password' => Hash::make('password'),
                'aktif'    => true,
            ],
            [
                'name'     => 'Staff Toko',
                'email'    => 'staff@sinaralam.id',
                'telepon'  => '08234567890',
                'role'     => 'staff',
                'password' => Hash::make('password'),
                'aktif'    => true,
            ],
            [
                'name'     => 'Budi Santoso',
                'email'    => 'user@sinaralam.id',
                'telepon'  => '08345678901',
                'role'     => 'user',
                'password' => Hash::make('password'),
                'aktif'    => true,
            ],
            [
                'name'     => 'Siti Rahayu',
                'email'    => 'siti@example.com',
                'telepon'  => '08456789012',
                'role'     => 'user',
                'password' => Hash::make('password'),
                'aktif'    => true,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(['email' => $userData['email']], $userData);
        }

        $this->command->info('✅ UserSeeder: ' . count($users) . ' user berhasil dibuat.');
    }
}