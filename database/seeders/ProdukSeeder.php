<?php

namespace Database\Seeders;

use App\Models\{Produk, Kategori};
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $kat = Kategori::pluck('id', 'slug');

        $produks = [
            // Material Dasar
            ['kategori_id'=>$kat['material-dasar'] ?? 1,'nama'=>'Semen Portland Tiga Roda 40kg','sku'=>'SMN-TR-40KG','harga'=>65000,'harga_promo'=>58000,'stok'=>320,'satuan'=>'sak','berat'=>40000,'jenis_pengiriman'=>'armada','unggulan'=>true,'ikon'=>'🧱','warna_bg'=>'#F0EDE7'],
            ['kategori_id'=>$kat['material-dasar'] ?? 1,'nama'=>'Semen Holcim Extra 40kg','sku'=>'SMN-HC-40KG','harga'=>67000,'stok'=>180,'satuan'=>'sak','berat'=>40000,'jenis_pengiriman'=>'armada','ikon'=>'🧱','warna_bg'=>'#F0EDE7'],
            ['kategori_id'=>$kat['material-dasar'] ?? 1,'nama'=>'Pasir Beton Grade A per m³','sku'=>'PSR-BTN-M3','harga'=>185000,'stok'=>48,'satuan'=>'m³','berat'=>1500000,'jenis_pengiriman'=>'armada','ikon'=>'🪨','warna_bg'=>'#F5F0EA'],
            ['kategori_id'=>$kat['material-dasar'] ?? 1,'nama'=>'Batu Bata Merah Jumbo','sku'=>'BTB-MRH-JMB','harga'=>900,'stok'=>25000,'satuan'=>'pcs','berat'=>2500,'jenis_pengiriman'=>'armada','unggulan'=>true,'ikon'=>'🔶','warna_bg'=>'#F5EAE4'],
            ['kategori_id'=>$kat['material-dasar'] ?? 1,'nama'=>'Batu Kali Belah per m³','sku'=>'BTK-BLH-M3','harga'=>210000,'stok'=>30,'satuan'=>'m³','berat'=>1800000,'jenis_pengiriman'=>'armada','ikon'=>'💧','warna_bg'=>'#EDF1F5'],
            ['kategori_id'=>$kat['material-dasar'] ?? 1,'nama'=>'Pasir Urug per m³','sku'=>'PSR-URG-M3','harga'=>120000,'stok'=>8,'satuan'=>'m³','berat'=>1400000,'jenis_pengiriman'=>'armada','ikon'=>'🏔️','warna_bg'=>'#F5EDE0'],

            // Besi & Baja
            ['kategori_id'=>$kat['besi-baja'] ?? 2,'nama'=>'Besi Beton Ulir D13mm 12m','sku'=>'BSI-BTN-D13','harga'=>98000,'harga_promo'=>92000,'stok'=>640,'satuan'=>'batang','berat'=>14800,'jenis_pengiriman'=>'armada','unggulan'=>true,'ikon'=>'🔩','warna_bg'=>'#E8EDF1'],
            ['kategori_id'=>$kat['besi-baja'] ?? 2,'nama'=>'Besi Beton Polos D10mm 12m','sku'=>'BSI-BTN-D10','harga'=>72000,'stok'=>480,'satuan'=>'batang','berat'=>9250,'jenis_pengiriman'=>'armada','ikon'=>'🔩','warna_bg'=>'#E8EDF1'],
            ['kategori_id'=>$kat['besi-baja'] ?? 2,'nama'=>'Wiremesh M8 2.1x5.4m','sku'=>'WMH-M8-210','harga'=>315000,'stok'=>120,'satuan'=>'lembar','berat'=>18000,'jenis_pengiriman'=>'armada','ikon'=>'⚙️','warna_bg'=>'#EBF0F4'],

            // Cat & Finishing
            ['kategori_id'=>$kat['cat-finishing'] ?? 5,'nama'=>'Cat Tembok Dulux Weathershield 5L','sku'=>'CAT-DLX-WS5L','harga'=>245000,'stok'=>12,'satuan'=>'kaleng','berat'=>6500,'jenis_pengiriman'=>'ekspedisi','unggulan'=>true,'ikon'=>'🪣','warna_bg'=>'#EEF2EC'],
            ['kategori_id'=>$kat['cat-finishing'] ?? 5,'nama'=>'Cat Tembok Jotun Majestic 5L','sku'=>'CAT-JTN-MJ5L','harga'=>225000,'stok'=>28,'satuan'=>'kaleng','berat'=>6200,'jenis_pengiriman'=>'ekspedisi','ikon'=>'🪣','warna_bg'=>'#EEF2EC'],
            ['kategori_id'=>$kat['cat-finishing'] ?? 5,'nama'=>'Cat Kayu Avian 1L','sku'=>'CAT-AVN-KY1L','harga'=>48000,'stok'=>55,'satuan'=>'kaleng','berat'=>1200,'jenis_pengiriman'=>'ekspedisi','ikon'=>'🪣','warna_bg'=>'#EEF2EC'],

            // Keramik
            ['kategori_id'=>$kat['keramik-granit'] ?? 4,'nama'=>'Keramik Roman Granite 60x60 Polished','sku'=>'KRM-RMN-6060','harga'=>89000,'stok'=>350,'satuan'=>'m²','berat'=>22000,'jenis_pengiriman'=>'ekspedisi','ikon'=>'🔲','warna_bg'=>'#EDF1F5'],
            ['kategori_id'=>$kat['keramik-granit'] ?? 4,'nama'=>'Keramik Asia Tile 40x40 Putih','sku'=>'KRM-AST-4040','harga'=>45000,'stok'=>500,'satuan'=>'m²','berat'=>18000,'jenis_pengiriman'=>'ekspedisi','ikon'=>'🔲','warna_bg'=>'#EDF1F5'],

            // Atap
            ['kategori_id'=>$kat['atap-plafon'] ?? 3,'nama'=>'Genteng Kanmuri Milenio','sku'=>'GNG-KNM-MLN','harga'=>8500,'stok'=>5000,'satuan'=>'pcs','berat'=>3200,'jenis_pengiriman'=>'armada','ikon'=>'🏠','warna_bg'=>'#F5EAE4'],
            ['kategori_id'=>$kat['atap-plafon'] ?? 3,'nama'=>'Plafon Gypsum Jayaboard 120x240cm','sku'=>'PLF-GYP-JYB','harga'=>65000,'stok'=>200,'satuan'=>'lembar','berat'=>12000,'jenis_pengiriman'=>'ekspedisi','ikon'=>'🏠','warna_bg'=>'#F0EDE8'],

            // Pipa
            ['kategori_id'=>$kat['pipa-sanitasi'] ?? 7,'nama'=>'Pipa PVC Wavin 4 inch AW 4m','sku'=>'PPA-PVC-WV4','harga'=>85000,'stok'=>150,'satuan'=>'batang','berat'=>4200,'jenis_pengiriman'=>'ekspedisi','ikon'=>'💧','warna_bg'=>'#EDF5F4'],
            ['kategori_id'=>$kat['pipa-sanitasi'] ?? 7,'nama'=>'Pipa PVC Wavin 2 inch AW 4m','sku'=>'PPA-PVC-WV2','harga'=>42000,'stok'=>280,'satuan'=>'batang','berat'=>2100,'jenis_pengiriman'=>'ekspedisi','ikon'=>'💧','warna_bg'=>'#EDF5F4'],
        ];

        foreach ($produks as $data) {
            Produk::updateOrCreate(
                ['sku' => $data['sku']],
                array_merge($data, [
                    'slug'        => Str::slug($data['nama']) . '-' . uniqid(),
                    'deskripsi'   => 'Produk berkualitas tinggi tersedia di Sinar Alam. Hubungi kami untuk info harga grosir dan pengiriman ke lokasi proyek Anda.',
                    'aktif'       => true,
                    'unggulan'    => $data['unggulan'] ?? false,
                    'terjual'     => rand(10, 500),
                ])
            );
        }

        $this->command->info('✅ ProdukSeeder: ' . count($produks) . ' produk berhasil dibuat.');
    }
}