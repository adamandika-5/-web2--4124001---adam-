<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama
        Produk::truncate();

        // Data produk toko bangunan
        $data = [
            [
                'nama' => 'Semen Gresik 50kg',
                'harga' => 65000,
                'stok' => 100,
                'kategori' => 'Semen',
                'deskripsi' => 'Semen berkualitas tinggi untuk konstruksi',
                'aktif' => true
            ],
            [
                'nama' => 'Pasir Beton per Kubik',
                'harga' => 350000,
                'stok' => 50,
                'kategori' => 'Pasir',
                'deskripsi' => 'Pasir beton halus untuk cor',
                'aktif' => true
            ],
            [
                'nama' => 'Bata Merah Press per 1000',
                'harga' => 850000,
                'stok' => 20,
                'kategori' => 'Bata',
                'deskripsi' => 'Bata merah press kualitas premium',
                'aktif' => true
            ],
            [
                'nama' => 'Keramik Granit 60x60',
                'harga' => 125000,
                'stok' => 200,
                'kategori' => 'Keramik',
                'deskripsi' => 'Keramik granit motif marmer',
                'aktif' => true
            ],
            [
                'nama' => 'Cat Tembok Dulux 25kg',
                'harga' => 750000,
                'stok' => 30,
                'kategori' => 'Cat',
                'deskripsi' => 'Cat tembok interior eksterior',
                'aktif' => true
            ],
            [
                'nama' => 'Pipa PVC 3 inch',
                'harga' => 85000,
                'stok' => 150,
                'kategori' => 'Pipa',
                'deskripsi' => 'Pipa PVC untuk saluran air',
                'aktif' => true
            ],
            [
                'nama' => 'Genteng Metal Pasir',
                'harga' => 95000,
                'stok' => 80,
                'kategori' => 'Genteng',
                'deskripsi' => 'Genteng metal warna pasir anti karat',
                'aktif' => true
            ],
            [
                'nama' => 'Pintu Kayu Jati',
                'harga' => 3500000,
                'stok' => 10,
                'kategori' => 'Pintu',
                'deskripsi' => 'Pintu kayu jati solid ukuran standar',
                'aktif' => true
            ],
        ];

        foreach ($data as $d) {
            Produk::create($d);
        }
    }
}