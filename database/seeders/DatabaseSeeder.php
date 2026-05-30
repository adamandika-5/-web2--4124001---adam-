<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            KategoriSeeder::class,
            OngkirZonaSeeder::class,
            ProdukSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('🎉 Sinar Alam — Semua seeder berhasil dijalankan!');
        $this->command->info('');
        $this->command->info('Akun default:');
        $this->command->info('  Admin   → admin@sinaralam.id  / password');
        $this->command->info('  Admin2  → staff@sinaralam.id  / password');
        $this->command->info('  User    → user@sinaralam.id   / password');
        $this->command->info('');
        $this->command->info('Jalankan: php artisan serve');
        $this->command->info('Buka    : http://127.0.0.1:8000');
    }
}