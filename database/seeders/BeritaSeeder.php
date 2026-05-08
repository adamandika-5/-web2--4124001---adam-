<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berita;

class BeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Berita::truncate();

        $data = [
            [
                'judul' => 'Tips Memilih Semen Berkualitas',
                'isi' => 'Semen adalah bahan bangunan utama yang harus dipilih dengan cermat. Pastikan kemasan tidak rusak dan cek tanggal produksi...',
                'penulis' => 'Admin Toko',
                'kategori' => 'Tips',
                'aktif' => true
            ],
            [
                'judul' => 'Promo Akhir Bulan Material Bangunan',
                'isi' => 'Dapatkan diskon hingga 20% untuk pembelian semen, pasir, dan bata. Promo berlaku hingga akhir bulan...',
                'penulis' => 'Marketing',
                'kategori' => 'Promo',
                'aktif' => true
            ],
            [
                'judul' => 'Cara Menghitung Kebutuhan Keramik',
                'isi' => 'Untuk menghitung kebutuhan keramik, ukur luas ruangan lalu tambahkan 10% untuk cadangan pemotongan...',
                'penulis' => 'Admin Toko',
                'kategori' => 'Tips',
                'aktif' => true
            ],
            [
                'judul' => 'Stok Baru Cat Tembok Premium',
                'isi' => 'Kami telah menerima stok baru cat tembok merek ternama dengan berbagai pilihan warna...',
                'penulis' => 'Admin Toko',
                'kategori' => 'Informasi',
                'aktif' => true
            ],
            [
                'judul' => 'Panduan Memilih Genteng yang Tepat',
                'isi' => 'Genteng metal lebih tahan lama namun genteng tanah liat lebih sejuk. Pilih sesuai kebutuhan dan budget...',
                'penulis' => 'Marketing',
                'kategori' => 'Tips',
                'aktif' => true
            ],
        ];

        foreach ($data as $d) {
            Berita::create($d);
        }
    }
}