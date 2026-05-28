@extends('layouts.app')

@section('title', 'Hubungi Kami')

@section('content')
<div class="legal-page" style="max-width:960px;margin:0 auto;padding:48px 24px 72px">
    <div style="text-align:center;margin-bottom:40px">
        <span class="section-label">Hubungi & Konsultasi</span>
        <h1 class="section-title" style="font-size:36px;margin-top:8px">Hubungi Kami</h1>
    </div>
    
    <div style="display:grid;grid-template-columns:1fr 1.2fr;gap:28px;align-items:start">
        
        {{-- KOLOM KIRI: Info Kontak --}}
        <div style="display:flex;flex-direction:column;gap:24px">
            <div class="card legal-content">
                <h3 style="margin-bottom:14px;border-bottom:1.5px solid var(--sand);padding-bottom:8px">📍 Lokasi Toko</h3>
                <p style="font-size:14px">
                    Jl. Brawijaya No.74/203, Peterongan, Kec. Peterongan, Kabupaten Jombang, Jawa Timur 61481
                </p>
                <div style="margin-top:14px;font-size:14px">
                    📞 (0343) 555-1234<br>
                    ✉️ info@sinaralam.id
                </div>
            </div>

            <div class="card legal-content">
                <h3 style="margin-bottom:14px;border-bottom:1.5px solid var(--sand);padding-bottom:8px">⏰ Jam Operasional</h3>
                <p style="font-size:14px">
                    Senin – Sabtu: 07:00 – 17:00 WIB<br>
                    Minggu & Hari Libur: Tutup
                </p>
            </div>
        </div>

        {{-- KOLOM KANAN: Form Konsultasi / Pesan --}}
        <div class="card legal-content">
            <h3 style="margin-bottom:16px;border-bottom:1.5px solid var(--sand);padding-bottom:8px">💬 Form Hubungi Kami / Konsultasi</h3>
            
            @if(session('success_contact'))
                <div class="alert alert-success">✓ {{ session('success_contact') }}</div>
            @endif

            <form action="#" method="GET" onsubmit="alert('Terima kasih! Pesan Anda telah terkirim. Admin kami akan segera menghubungi Anda.');">
                <div class="form-grp">
                    <label class="form-lbl">Nama Lengkap *</label>
                    <input type="text" class="form-inp" required placeholder="Masukkan nama Anda">
                </div>
                
                <div class="form-grp">
                    <label class="form-lbl">Alamat Email / No. WhatsApp *</label>
                    <input type="text" class="form-inp" required placeholder="cth: 08123456789 / nama@email.com">
                </div>

                <div class="form-grp">
                    <label class="form-lbl">Pesan / Rencana Kebutuhan Material *</label>
                    <textarea class="form-inp" required rows="4" placeholder="Tulis pesan atau pertanyaan Anda di sini..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                    Kirim Pesan & Konsultasi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
