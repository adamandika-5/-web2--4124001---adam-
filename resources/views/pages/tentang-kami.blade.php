@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')
<div class="legal-page" style="max-width:960px;margin:0 auto;padding:48px 24px 72px">
    <div style="text-align:center;margin-bottom:40px">
        <span class="section-label">Profil Perusahaan</span>
        <h1 class="section-title" style="font-size:36px;margin-top:8px">Tentang Sinar Alam</h1>
    </div>
    
    <div class="card legal-content">
        <p>
            <strong>Toko Bangunan Sinar Alam</strong> adalah toko material bangunan terpercaya di Jombang, Jawa Timur, yang didirikan pada tahun 2019. Kami menyediakan berbagai macam bahan bangunan berkualitas tinggi, mulai dari material dasar seperti semen, pasir, dan besi beton, hingga finishing interior seperti keramik mewah dan cat pelapis terbaik.
        </p>
        <p>
            Kami berkomitmen untuk mendukung pembangunan infrastruktur dan hunian impian Anda dengan menyediakan produk berkualitas SNI, layanan pengiriman armada mandiri yang handal, serta program penyewaan alat konstruksi modern yang terjangkau.
        </p>
        
        <h3>Visi & Misi</h3>
        <ul style="padding-left:20px;margin-bottom:20px">
            <li><strong>Visi:</strong> Menjadi penyedia material bangunan dan alat konstruksi terlengkap, tepercaya, dan terdepan di wilayah Jawa Timur.</li>
            <li><strong>Misi:</strong> Memberikan pelayanan prima, menjamin ketersediaan barang berkualitas tinggi, dan mempermudah proses logistik proyek konstruksi pelanggan.</li>
        </ul>

        <h3>Kenapa Memilih Kami?</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:16px">
            <div style="background:var(--oat);padding:16px;border-radius:var(--r-sm)">
                <strong style="color:var(--soil)">💯 Jaminan Kualitas</strong>
                <p style="font-size:13.5px;margin-top:6px;color:var(--soil-light)">Semua produk kami berasal dari merek-merek ternama yang sudah teruji kekuatan dan durabilitasnya.</p>
            </div>
            <div style="background:var(--oat);padding:16px;border-radius:var(--r-sm)">
                <strong style="color:var(--soil)">🚚 Pengiriman Cepat</strong>
                <p style="font-size:13.5px;margin-top:6px;color:var(--soil-light)">Dukungan armada angkutan mandiri memastikan material Anda tiba di lokasi proyek tepat waktu.</p>
            </div>
        </div>
    </div>
</div>
@endsection
