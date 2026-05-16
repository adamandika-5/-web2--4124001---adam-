@extends('layouts.app')
@section('title', $alat->nama . ' — Sewa Alat')

@section('content')
<div style="max-width:1280px;margin:0 auto;padding:28px 48px 64px">

    {{-- Breadcrumb --}}
    <nav style="font-size:13px;color:var(--clay);display:flex;align-items:center;gap:7px;margin-bottom:28px;flex-wrap:wrap">
        <a href="{{ route('beranda') }}" style="color:var(--terracotta);text-decoration:none;font-weight:600">Beranda</a>
        <span>›</span>
        <a href="{{ route('sewa.index') }}" style="color:var(--terracotta);text-decoration:none;font-weight:600">Sewa Alat</a>
        <span>›</span>
        <span>{{ $alat->nama }}</span>
    </nav>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:52px;align-items:start">

        {{-- Gambar --}}
        <div>
            <div style="background:linear-gradient(135deg,#EFF3F6,#E4EBF0);border-radius:var(--r-xl);aspect-ratio:4/3;display:flex;align-items:center;justify-content:center;font-size:120px;border:1.5px solid rgba(176,139,110,.12);box-shadow:var(--sh-md);overflow:hidden">
                @if($alat->gambar)
                    <img src="{{ asset('storage/'.$alat->gambar) }}" alt="{{ $alat->nama }}" style="width:100%;height:100%;object-fit:cover">
                @else
                    🔧
                @endif
            </div>

            {{-- Status & kondisi --}}
            <div style="display:flex;gap:10px;margin-top:14px;flex-wrap:wrap">
                <span class="status-pill {{ $alat->tersedia > 0 ? 's-lunas' : 's-pending' }}" style="font-size:13px;padding:6px 14px">
                    {{ $alat->tersedia > 0 ? '✓ Tersedia '.$alat->tersedia.' Unit' : 'Semua Unit Disewa' }}
                </span>
                <span class="badge {{ $alat->kondisi === 'baik' ? 'badge-new' : 'badge-low' }}" style="font-size:12px;padding:5px 12px">
                    Kondisi: {{ ucfirst($alat->kondisi) }}
                </span>
                @if($alat->kategori_alat)
                    <span class="badge" style="background:rgba(176,139,110,.1);color:var(--clay);font-size:12px;padding:5px 12px">
                        {{ $alat->kategori_alat }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Info --}}
        <div>
            <h1 style="font-family:var(--fd);font-size:clamp(22px,2.5vw,32px);font-weight:500;color:var(--soil);line-height:1.2;margin-bottom:16px">
                {{ $alat->nama }}
            </h1>

            @if($alat->deskripsi)
                <p style="font-size:14.5px;color:var(--soil-light);line-height:1.75;margin-bottom:22px">
                    {{ $alat->deskripsi }}
                </p>
            @endif

            {{-- Tarif --}}
            <div style="background:var(--oat);border-radius:var(--r-lg);padding:20px;margin-bottom:22px;border:1px solid rgba(176,139,110,.12)">
                <div style="font-size:12.5px;font-weight:700;color:var(--clay);margin-bottom:14px;text-transform:uppercase;letter-spacing:.06em">Tarif Sewa</div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">
                    <div style="text-align:center;padding:14px;background:#fff;border-radius:var(--r-md);border:1px solid rgba(176,139,110,.1)">
                        <div style="font-family:var(--fd);font-size:20px;font-weight:700;color:var(--terracotta)">Rp {{ number_format($alat->tarif_harian,0,',','.') }}</div>
                        <div style="font-size:11.5px;color:var(--clay);margin-top:4px">per Hari</div>
                    </div>
                    @if($alat->tarif_mingguan)
                    <div style="text-align:center;padding:14px;background:#fff;border-radius:var(--r-md);border:1px solid rgba(176,139,110,.1)">
                        <div style="font-family:var(--fd);font-size:20px;font-weight:700;color:var(--soil)">Rp {{ number_format($alat->tarif_mingguan,0,',','.') }}</div>
                        <div style="font-size:11.5px;color:var(--clay);margin-top:4px">per Minggu</div>
                    </div>
                    @endif
                    @if($alat->tarif_bulanan)
                    <div style="text-align:center;padding:14px;background:#fff;border-radius:var(--r-md);border:1px solid rgba(176,139,110,.1)">
                        <div style="font-family:var(--fd);font-size:20px;font-weight:700;color:var(--soil)">Rp {{ number_format($alat->tarif_bulanan,0,',','.') }}</div>
                        <div style="font-size:11.5px;color:var(--clay);margin-top:4px">per Bulan</div>
                    </div>
                    @endif
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:12px;padding-top:12px;border-top:1px solid rgba(176,139,110,.12)">
                    @if($alat->deposit > 0)
                    <div style="display:flex;justify-content:space-between;font-size:13px">
                        <span style="color:var(--clay)">Deposit</span>
                        <span style="font-weight:700;color:var(--soil)">Rp {{ number_format($alat->deposit,0,',','.') }}</span>
                    </div>
                    @endif
                    @if($alat->denda_per_hari > 0)
                    <div style="display:flex;justify-content:space-between;font-size:13px">
                        <span style="color:var(--clay)">Denda/hari telat</span>
                        <span style="font-weight:700;color:#c03030">Rp {{ number_format($alat->denda_per_hari,0,',','.') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Form Booking --}}
            @if($alat->tersedia > 0 && $alat->kondisi !== 'perbaikan')
            @auth
            <form action="{{ route('sewa.booking', $alat->slug) }}" method="POST" id="formBooking">
                @csrf
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px">
                    <div class="form-grp" style="margin-bottom:0">
                        <label class="form-lbl">Tanggal Mulai *</label>
                        <input type="date" name="tanggal_mulai" id="tglMulai" class="form-inp"
                               min="{{ date('Y-m-d') }}" required onchange="hitungTotal()">
                    </div>
                    <div class="form-grp" style="margin-bottom:0">
                        <label class="form-lbl">Tanggal Selesai *</label>
                        <input type="date" name="tanggal_selesai" id="tglSelesai" class="form-inp"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" required onchange="hitungTotal()">
                    </div>
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Alamat Penggunaan Alat</label>
                    <input type="text" name="alamat" class="form-inp"
                           placeholder="Lokasi proyek atau alamat pengiriman alat">
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Catatan Tambahan</label>
                    <textarea name="catatan" class="form-inp" rows="2"
                              placeholder="Informasi tambahan..."></textarea>
                </div>

                {{-- Estimasi biaya --}}
                <div id="estimasiWrap" style="display:none;background:rgba(198,107,61,.06);border:1.5px solid rgba(198,107,61,.2);border-radius:var(--r-md);padding:14px;margin-bottom:16px">
                    <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px">
                        <span style="color:var(--clay)">Durasi sewa</span>
                        <span id="estDurasi" style="font-weight:700;color:var(--soil)">—</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px">
                        <span style="color:var(--clay)">Biaya sewa</span>
                        <span id="estSewa" style="font-weight:700;color:var(--soil)">—</span>
                    </div>
                    @if($alat->deposit > 0)
                    <div style="display:flex;justify-content:space-between;font-size:13px;border-top:1px solid rgba(198,107,61,.15);padding-top:8px;margin-top:6px">
                        <span style="color:var(--clay)">+ Deposit</span>
                        <span style="font-weight:700;color:var(--soil)">Rp {{ number_format($alat->deposit,0,',','.') }}</span>
                    </div>
                    @endif
                    <div style="display:flex;justify-content:space-between;font-size:15px;border-top:2px solid rgba(198,107,61,.2);padding-top:10px;margin-top:8px">
                        <span style="font-weight:700;color:var(--soil)">Total Dibayar</span>
                        <span id="estTotal" style="font-family:var(--fd);font-size:20px;font-weight:700;color:var(--terracotta)">—</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;font-size:15px;padding:14px">
                    📅 Booking Sekarang
                </button>
            </form>
            @else
            <a href="{{ route('login') }}" class="btn btn-primary" style="width:100%;justify-content:center;font-size:15px;padding:14px">
                🔐 Login untuk Booking
            </a>
            @endauth
            @else
            <div style="background:rgba(192,48,48,.06);border-radius:var(--r-lg);padding:20px;text-align:center;border:1.5px solid rgba(192,48,48,.2)">
                <div style="font-size:32px;margin-bottom:8px">🔨</div>
                <div style="font-size:14px;font-weight:700;color:#c03030;margin-bottom:4px">Alat Tidak Tersedia</div>
                <div style="font-size:13px;color:var(--clay)">Semua unit sedang disewa atau dalam perbaikan</div>
            </div>
            @endif

            {{-- WA --}}
            <a href="https://wa.me/{{ config('app.whatsapp_number','6281234567890') }}?text=Halo+Sinar+Alam,+saya+ingin+tanya+soal+sewa+{{ urlencode($alat->nama) }}"
               target="_blank"
               style="display:flex;align-items:center;gap:10px;padding:13px 18px;background:#F0FDF4;border:1.5px solid rgba(37,211,102,.3);border-radius:var(--r-md);text-decoration:none;font-size:13.5px;font-weight:600;color:#166534;margin-top:12px;transition:all .2s"
               onmouseover="this.style.background='#DCFCE7'" onmouseout="this.style.background='#F0FDF4'">
                <span style="font-size:20px">💬</span> Tanya ketersediaan & harga grosir via WhatsApp
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
const tarifHarian = {{ $alat->tarif_harian }};
const deposit     = {{ $alat->deposit }};

function hitungTotal() {
    const m = document.getElementById('tglMulai').value;
    const s = document.getElementById('tglSelesai').value;
    if (!m || !s) return;
    const d1 = new Date(m), d2 = new Date(s);
    if (d2 <= d1) return;
    const durasi    = Math.floor((d2-d1)/86400000) + 1;
    const totalSewa = durasi * tarifHarian;
    const total     = totalSewa + deposit;
    document.getElementById('estDurasi').textContent = durasi + ' hari';
    document.getElementById('estSewa').textContent   = 'Rp ' + totalSewa.toLocaleString('id-ID');
    document.getElementById('estTotal').textContent  = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('estimasiWrap').style.display = 'block';
    // Update min tanggal selesai
    document.getElementById('tglSelesai').min = new Date(new Date(m).getTime() + 86400000).toISOString().split('T')[0];
}
</script>
@endpush
@endsection