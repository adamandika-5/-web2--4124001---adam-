<?php

namespace Database\Seeders;

use App\Models\{Kategori, SubKategori};
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            [
                'nama'   => 'Material Dasar',
                'slug'   => 'material-dasar',
                'ikon'   => '🪨',
                'warna'  => '#8A8A80',
                'urutan' => 1,
                'subs'   => ['Semen', 'Pasir', 'Batu Bata', 'Batu Kali', 'Kerikil', 'Mortar'],
            ],
            [
                'nama'   => 'Besi & Baja',
                'slug'   => 'besi-baja',
                'ikon'   => '⚙️',
                'warna'  => '#5A6472',
                'urutan' => 2,
                'subs'   => ['Besi Beton', 'Besi Hollow', 'Baja Ringan', 'Wiremesh', 'Kawat Beton'],
            ],
            [
                'nama'   => 'Atap & Plafon',
                'slug'   => 'atap-plafon',
                'ikon'   => '🏠',
                'warna'  => '#8B6E4A',
                'urutan' => 3,
                'subs'   => ['Genteng Tanah Liat', 'Genteng Metal', 'Asbes', 'Plafon Gypsum', 'Plafon PVC', 'Rangka Atap'],
            ],
            [
                'nama'   => 'Keramik & Granit',
                'slug'   => 'keramik-granit',
                'ikon'   => '🔲',
                'warna'  => '#7B9BAE',
                'urutan' => 4,
                'subs'   => ['Keramik Lantai', 'Keramik Dinding', 'Granit Polished', 'Granit Matte', 'Mozaik'],
            ],
            [
                'nama'   => 'Cat & Finishing',
                'slug'   => 'cat-finishing',
                'ikon'   => '🪣',
                'warna'  => '#C66B3D',
                'urutan' => 5,
                'subs'   => ['Cat Tembok', 'Cat Kayu', 'Cat Besi', 'Waterproof', 'Plamir', 'Dempul'],
            ],
            [
                'nama'   => 'Kayu & Triplek',
                'slug'   => 'kayu-triplek',
                'ikon'   => '🌲',
                'warna'  => '#9A7040',
                'urutan' => 6,
                'subs'   => ['Kayu Meranti', 'Kayu Jati', 'Triplek', 'Multiplex', 'MDF', 'Papan GRC'],
            ],
            [
                'nama'   => 'Pipa & Sanitasi',
                'slug'   => 'pipa-sanitasi',
                'ikon'   => '💧',
                'warna'  => '#4A8B7F',
                'urutan' => 7,
                'subs'   => ['Pipa PVC', 'Pipa HDPE', 'Fitting Pipa', 'Kloset', 'Wastafel', 'Shower'],
            ],
            [
                'nama'   => 'Alat Listrik',
                'slug'   => 'alat-listrik',
                'ikon'   => '⚡',
                'warna'  => '#C08E3A',
                'urutan' => 8,
                'subs'   => ['Kabel Listrik', 'Stop Kontak', 'Saklar', 'MCB', 'Lampu', 'Conduit'],
            ],
        ];

        foreach ($kategoris as $katData) {
            $subs = $katData['subs'];
            unset($katData['subs']);

            $kategori = Kategori::updateOrCreate(
                ['slug' => $katData['slug']],
                array_merge($katData, ['aktif' => true])
            );

            foreach ($subs as $i => $subNama) {
                SubKategori::updateOrCreate(
                    ['slug' => \Str::slug($subNama) . '-' . $kategori->id],
                    [
                        'kategori_id' => $kategori->id,
                        'nama'        => $subNama,
                        'aktif'       => true,
                    ]
                );
            }
        }

        $this->command->info('✅ KategoriSeeder: ' . count($kategoris) . ' kategori + sub-kategori berhasil dibuat.');
    }
}