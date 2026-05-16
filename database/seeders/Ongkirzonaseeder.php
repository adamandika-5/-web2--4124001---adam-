<?php

namespace Database\Seeders;

use App\Models\OngkirZona;
use Illuminate\Database\Seeder;

class OngkirZonaSeeder extends Seeder
{
    public function run(): void
    {
        // Zona pengiriman armada Sinar Alam — Jawa Timur
        // Tarif: Pickup (L300), Engkel, Truk Fuso
        $zonas = [
            // ── ZONA 1: Pasuruan & sekitar (< 20 km) ──
            ['kota'=>'Pasuruan',        'zona'=>'1','jarak'=>5,   'pickup'=>25000,  'engkel'=>50000,  'truk'=>100000],
            ['kota'=>'Bangil',          'zona'=>'1','jarak'=>10,  'pickup'=>30000,  'engkel'=>60000,  'truk'=>120000],
            ['kota'=>'Pandaan',         'zona'=>'1','jarak'=>15,  'pickup'=>35000,  'engkel'=>70000,  'truk'=>140000],
            ['kota'=>'Gempol',          'zona'=>'1','jarak'=>18,  'pickup'=>40000,  'engkel'=>75000,  'truk'=>150000],

            // ── ZONA 2: Kab. Pasuruan & Probolinggo (20–50 km) ──
            ['kota'=>'Probolinggo',     'zona'=>'2','jarak'=>35,  'pickup'=>60000,  'engkel'=>120000, 'truk'=>220000],
            ['kota'=>'Kraksaan',        'zona'=>'2','jarak'=>45,  'pickup'=>70000,  'engkel'=>140000, 'truk'=>260000],
            ['kota'=>'Lawang',          'zona'=>'2','jarak'=>25,  'pickup'=>50000,  'engkel'=>100000, 'truk'=>190000],
            ['kota'=>'Purwosari',       'zona'=>'2','jarak'=>22,  'pickup'=>45000,  'engkel'=>90000,  'truk'=>170000],
            ['kota'=>'Gondangwetan',    'zona'=>'2','jarak'=>20,  'pickup'=>42000,  'engkel'=>85000,  'truk'=>160000],

            // ── ZONA 3: Malang, Sidoarjo, Gresik (50–100 km) ──
            ['kota'=>'Malang',          'zona'=>'3','jarak'=>60,  'pickup'=>90000,  'engkel'=>180000, 'truk'=>320000],
            ['kota'=>'Batu',            'zona'=>'3','jarak'=>75,  'pickup'=>100000, 'engkel'=>200000, 'truk'=>360000],
            ['kota'=>'Sidoarjo',        'zona'=>'3','jarak'=>55,  'pickup'=>85000,  'engkel'=>170000, 'truk'=>300000],
            ['kota'=>'Gresik',          'zona'=>'3','jarak'=>90,  'pickup'=>110000, 'engkel'=>210000, 'truk'=>380000],
            ['kota'=>'Mojokerto',       'zona'=>'3','jarak'=>70,  'pickup'=>95000,  'engkel'=>190000, 'truk'=>340000],
            ['kota'=>'Jombang',         'zona'=>'3','jarak'=>80,  'pickup'=>105000, 'engkel'=>200000, 'truk'=>360000],

            // ── ZONA 4: Surabaya & Kediri (100–180 km) ──
            ['kota'=>'Surabaya',        'zona'=>'4','jarak'=>70,  'pickup'=>100000, 'engkel'=>200000, 'truk'=>360000],
            ['kota'=>'Kediri',          'zona'=>'4','jarak'=>130, 'pickup'=>140000, 'engkel'=>270000, 'truk'=>480000],
            ['kota'=>'Blitar',          'zona'=>'4','jarak'=>120, 'pickup'=>135000, 'engkel'=>260000, 'truk'=>460000],
            ['kota'=>'Lumajang',        'zona'=>'4','jarak'=>110, 'pickup'=>130000, 'engkel'=>250000, 'truk'=>440000],
            ['kota'=>'Jember',          'zona'=>'4','jarak'=>160, 'pickup'=>160000, 'engkel'=>300000, 'truk'=>540000],
            ['kota'=>'Lamongan',        'zona'=>'4','jarak'=>120, 'pickup'=>140000, 'engkel'=>270000, 'truk'=>480000],
            ['kota'=>'Bojonegoro',      'zona'=>'4','jarak'=>150, 'pickup'=>155000, 'engkel'=>290000, 'truk'=>520000],
            ['kota'=>'Tuban',           'zona'=>'4','jarak'=>160, 'pickup'=>160000, 'engkel'=>300000, 'truk'=>540000],
            ['kota'=>'Nganjuk',         'zona'=>'4','jarak'=>140, 'pickup'=>145000, 'engkel'=>280000, 'truk'=>500000],
            ['kota'=>'Tulungagung',     'zona'=>'4','jarak'=>140, 'pickup'=>145000, 'engkel'=>280000, 'truk'=>500000],

            // ── ZONA 5: Ujung Jawa Timur (> 180 km) ──
            ['kota'=>'Banyuwangi',      'zona'=>'5','jarak'=>260, 'pickup'=>220000, 'engkel'=>420000, 'truk'=>750000],
            ['kota'=>'Situbondo',       'zona'=>'5','jarak'=>220, 'pickup'=>200000, 'engkel'=>380000, 'truk'=>680000],
            ['kota'=>'Bondowoso',       'zona'=>'5','jarak'=>200, 'pickup'=>185000, 'engkel'=>360000, 'truk'=>640000],
            ['kota'=>'Madiun',          'zona'=>'5','jarak'=>210, 'pickup'=>190000, 'engkel'=>370000, 'truk'=>660000],
            ['kota'=>'Ngawi',           'zona'=>'5','jarak'=>230, 'pickup'=>205000, 'engkel'=>395000, 'truk'=>700000],
            ['kota'=>'Magetan',         'zona'=>'5','jarak'=>220, 'pickup'=>200000, 'engkel'=>385000, 'truk'=>680000],
            ['kota'=>'Pacitan',         'zona'=>'5','jarak'=>240, 'pickup'=>210000, 'engkel'=>405000, 'truk'=>720000],
            ['kota'=>'Ponorogo',        'zona'=>'5','jarak'=>195, 'pickup'=>182000, 'engkel'=>355000, 'truk'=>630000],
            ['kota'=>'Trenggalek',      'zona'=>'5','jarak'=>180, 'pickup'=>175000, 'engkel'=>340000, 'truk'=>600000],
            ['kota'=>'Sampang',         'zona'=>'5','jarak'=>180, 'pickup'=>175000, 'engkel'=>340000, 'truk'=>600000],
            ['kota'=>'Pamekasan',       'zona'=>'5','jarak'=>195, 'pickup'=>182000, 'engkel'=>355000, 'truk'=>630000],
            ['kota'=>'Sumenep',         'zona'=>'5','jarak'=>230, 'pickup'=>210000, 'engkel'=>400000, 'truk'=>720000],
        ];

        foreach ($zonas as $z) {
            OngkirZona::updateOrCreate(
                ['kota' => $z['kota']],
                [
                    'provinsi'        => 'Jawa Timur',
                    'zona'            => $z['zona'],
                    'jarak_km'        => $z['jarak'],
                    'tarif_pickup'    => $z['pickup'],
                    'tarif_engkel'    => $z['engkel'],
                    'tarif_truk'      => $z['truk'],
                    'tersedia_armada' => true,
                ]
            );
        }

        $this->command->info('✅ OngkirZonaSeeder: ' . count($zonas) . ' zona ongkir Jawa Timur berhasil dibuat.');
    }
}