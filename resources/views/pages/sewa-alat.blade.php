@extends('layouts.app')
@section('title', 'Sewa Alat Bangunan')

@section('content')

{{-- Hero --}}
<section style="background:var(--soil);padding:56px 48px;position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;background-image:radial-gradient(ellipse 50% 80% at 80% 50%,rgba(192,142,58,.28) 0%,transparent 60%),radial-gradient(ellipse 40% 60% at 10% 80%,rgba(198,107,61,.18) 0%,transparent 55%)"></div>
    <div class="grain"></div>
    <div style="max-width:1280px;margin:0 auto;position:relative;z-index:2">
        <div class="section-label" style="color:var(--clay-light)">Layanan Penyewaan</div>
        <h1 style="font-family:var(--fs);font-size:clamp(28px,4vw,48px);font-weight:700;color:var(--sand);margin:10px 0 12px;line-height:1.1">
            Sewa Alat Bangunan,<br><em style="color:var(--terracotta);font-style:italic">Harga Harian.</em>
        </h1>
        <p style="font-size:15px;color:rgba(232,220,199,.55);max-width:480px;margin-bottom:28px">
            Tersedia scaffolding, concrete mixer, alat potong, dan 80+ jenis alat bangunan. Booking online, dikirim ke lokasi proyek Anda di Jawa Timur.
        </p>
        <div style="display:flex;gap:24px;font-size:13px;color:rgba(232,220,199,.5);flex-wrap:wrap">
            <span>🔧 {{ $totalAlat }}+ jenis alat</span>
            <span>📅 Booking online 24 jam</span>
            <span>🚚 Antar-jemput lokasi</span>
            <span>🛡️ Deposit & asuransi tersedia</span>
        </div>
    </div>
</section>

{{-- Filter & Grid --}}
<div class="home-section" style="padding-top:36px">

    {{-- Filter bar --}}
    <form method="GET" action="{{ route('sewa.index') }}" style="display:flex;gap:10px;align-items:center;margin-bottom:28px;flex-wrap:wrap">
        <div style="position:relative;flex:1;min-width:220px">
            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--clay);pointer-events:none;width:15px;height:15px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama alat..."
                   style="width:100%;padding:9px 14px 9px 36px;border:1.5px solid var(--sand);border-radius:var(--r-md);background:#fff;font-family:var(--fb);font-size:13px;color:var(--soil);outline:none;transition:border-color .2s"
                   onfocus="this.style.borderColor='var(--terracotta)'" onblur="this.style.borderColor='var(--sand)'">
        </div>
        <select name="kategori" onchange="this.form.submit()"
                style="padding:9px 12px;border:1.5px solid var(--sand);border-radius:var(--r-md);background:#fff;font-family:var(--fb);font-size:13px;color:var(--soil);outline:none;cursor:pointer">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $kat)
                <option value="{{ $kat }}" {{ request('kategori') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
            @endforeach
        </select>
        <select name="status" onchange="this.form.submit()"
                style="padding:9px 12px;border:1.5px solid var(--sand);border-radius:var(--r-md);background:#fff;font-family:var(--fb);font-size:13px;color:var(--soil);outline:none;cursor:pointer">
            <option value="">Semua Status</option>
            <option value="tersedia" {{ request('status')==='tersedia'?'selected':'' }}>Tersedia Sekarang</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Cari</button>
    </form>

    {{-- Grid Alat --}}
    <div class="sewa-grid">
        @forelse($alat as $a)
        <div style="background:#fff;border-radius:var(--r-lg);overflow:hidden;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08);transition:all .3s var(--ease)"
             onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='var(--sh-lg)'"
             onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='var(--sh-sm)'">

            {{-- Gambar --}}
            <a href="{{ route('sewa.show', $a->slug) }}" style="display:block;text-decoration:none">
                <div style="background:linear-gradient(135deg,#EFF3F6,#E4EBF0);aspect-ratio:16/9;display:flex;align-items:center;justify-content:center;font-size:64px;position:relative;overflow:hidden">
                    @if($a->gambar)
                        <img src="{{ asset('storage/'.$a->gambar) }}" alt="{{ $a->nama }}" style="width:100%;height:100%;object-fit:cover">
                    @else
                        🔧
                    @endif
                    {{-- Status badge --}}
                    <span style="position:absolute;top:12px;right:12px;font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;
                          {{ $a->tersedia > 0
                              ? 'background:rgba(96,108,56,.15);color:var(--moss);border:1px solid rgba(96,108,56,.25)'
                              : 'background:rgba(192,142,58,.15);color:var(--ochre);border:1px solid rgba(192,142,58,.25)' }}">
                        {{ $a->tersedia > 0 ? '✓ Tersedia' : 'Semua Disewa' }}
                    </span>
                    @if($a->kondisi === 'perbaikan')
                        <span style="position:absolute;top:12px;left:12px;font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;background:rgba(192,48,48,.12);color:#c03030">🔨 Perbaikan</span>
                    @endif
                </div>
            </a>

            <div style="padding:18px">
                <div style="font-size:11px;color:var(--clay);font-weight:700;letter-spacing:.04em;text-transform:uppercase;margin-bottom:5px">
                    {{ $a->kategori_alat ?? 'Alat Bangunan' }}
                </div>
                <a href="{{ route('sewa.show', $a->slug) }}" style="font-family:var(--fd);font-size:16px;font-weight:500;color:var(--soil);text-decoration:none;display:block;margin-bottom:12px;line-height:1.3">
                    {{ $a->nama }}
                </a>

                {{-- Harga --}}
                <div style="display:flex;gap:16px;margin-bottom:12px">
                    <div>
                        <div style="font-family:var(--fd);font-size:20px;font-weight:700;color:var(--terracotta)">
                            Rp {{ number_format($a->tarif_harian, 0, ',', '.') }}
                        </div>
                        <div style="font-size:11px;color:var(--clay)">/hari</div>
                    </div>
                    @if($a->tarif_mingguan)
                    <div style="width:1px;background:var(--sand)"></div>
                    <div>
                        <div style="font-size:14px;font-weight:700;color:var(--soil)">Rp {{ number_format($a->tarif_mingguan, 0, ',', '.') }}</div>
                        <div style="font-size:11px;color:var(--clay)">/minggu</div>
                    </div>
                    @endif
                </div>

                @if($a->deskripsi)
                <div style="font-size:12.5px;color:var(--clay);margin-bottom:14px;line-height:1.6">
                    {{ Str::limit($a->deskripsi, 80) }}
                </div>
                @endif

                @if($a->deposit > 0)
                <div style="font-size:11.5px;color:var(--concrete);margin-bottom:12px">
                    Deposit: Rp {{ number_format($a->deposit, 0, ',', '.') }}
                    @if($a->denda_per_hari > 0)
                        · Denda/hari: Rp {{ number_format($a->denda_per_hari, 0, ',', '.') }}
                    @endif
                </div>
                @endif

                <div style="display:flex;gap:8px">
                    @if($a->tersedia > 0 && $a->kondisi !== 'perbaikan')
                        <a href="{{ route('sewa.show', $a->slug) }}" class="btn btn-primary btn-sm" style="flex:1;justify-content:center">
                            Pesan Sekarang
                        </a>
                    @else
                        <button class="btn btn-secondary btn-sm" style="flex:1;justify-content:center;opacity:.6;cursor:not-allowed" disabled>
                            Tidak Tersedia
                        </button>
                    @endif
                    <a href="{{ route('sewa.show', $a->slug) }}" class="btn btn-secondary btn-sm" style="padding:7px 14px">Detail</a>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:80px;background:#fff;border-radius:var(--r-xl)">
            <div style="font-size:48px;margin-bottom:12px">🔧</div>
            @if(request()->filled('q') || request()->filled('kategori') || request()->filled('status'))
                <div style="font-family:var(--fd);font-size:20px;color:var(--soil);margin-bottom:8px">Alat tidak ditemukan</div>
                <div style="font-size:13.5px;color:var(--clay);margin-bottom:20px">Coba ubah filter atau kata kunci</div>
                <a href="{{ route('sewa.index') }}" class="btn btn-primary">Lihat Semua Alat</a>
            @else
                <div style="font-family:var(--fd);font-size:20px;color:var(--soil);margin-bottom:8px">Belum ada alat sewa tersedia</div>
                <div style="font-size:13.5px;color:var(--clay);margin-bottom:20px">Silakan kembali lagi nanti atau cari material lainnya.</div>
                <div style="display:flex;gap:12px;justify-content:center">
                    <a href="{{ route('beranda') }}" class="btn btn-primary">Kembali ke Beranda</a>
                    <a href="{{ route('katalog.index') }}" class="btn btn-secondary">Lihat Katalog Produk</a>
                </div>
            @endif
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($alat->hasPages())
    <div style="display:flex;justify-content:center;gap:6px;margin-top:36px">
        @if(!$alat->onFirstPage())
            <a href="{{ $alat->previousPageUrl() }}" class="pag-btn">‹</a>
        @endif
        @foreach($alat->getUrlRange(max(1,$alat->currentPage()-2), min($alat->lastPage(),$alat->currentPage()+2)) as $page => $url)
            @if($page == $alat->currentPage())
                <button class="pag-btn active">{{ $page }}</button>
            @else
                <a href="{{ $url }}" class="pag-btn">{{ $page }}</a>
            @endif
        @endforeach
        @if($alat->hasMorePages())
            <a href="{{ $alat->nextPageUrl() }}" class="pag-btn">›</a>
        @endif
    </div>
    @endif

    {{-- Kalkulator Biaya --}}
    <div class="sewa-kalkulator-wrap">
        <div style="position:absolute;inset:0;background:radial-gradient(ellipse 60% 70% at 85% 30%,rgba(198,107,61,.22) 0%,transparent 55%)"></div>
        <div class="grain"></div>
        <div class="sewa-kalkulator-inner">
            <div>
                <div class="section-label" style="color:var(--clay-light)">Kalkulator Biaya</div>
                <h3 style="font-family:var(--fs);font-size:28px;font-weight:700;color:var(--sand);margin:8px 0 12px">
                    Hitung <em style="color:var(--terracotta);font-style:italic">Biaya Sewa</em> Sebelum Booking
                </h3>
                <p style="font-size:13.5px;color:rgba(232,220,199,.5);line-height:1.7">
                    Pilih alat dan tanggal sewa — sistem kami akan menghitung total biaya secara otomatis, termasuk potensi denda keterlambatan jika pengembalian melewati jadwal.
                </p>
            </div>
            <div style="background:rgba(255,255,255,.06);border:1px solid rgba(232,220,199,.12);border-radius:var(--r-lg);padding:26px;backdrop-filter:blur(10px)">
                <div style="margin-bottom:14px">
                    <div style="font-size:12px;font-weight:700;color:rgba(232,220,199,.6);margin-bottom:6px">Pilih Alat</div>
                    <select id="kalkulasiAlat" onchange="kalkulasi()"
                            style="width:100%;padding:10px 14px;background:rgba(255,255,255,.08);border:1px solid rgba(232,220,199,.2);border-radius:var(--r-md);color:var(--sand);font-family:var(--fb);font-size:13px;outline:none">
                        <option value="">-- Pilih alat --</option>
                        @foreach($alat as $a)
                        <option value="{{ $a->tarif_harian }}" data-nama="{{ $a->nama }}" data-deposit="{{ $a->deposit }}" data-slug="{{ $a->slug }}">
                            {{ $a->nama }} — Rp {{ number_format($a->tarif_harian, 0, ',', '.') }}/hari
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="sewa-date-grid">
                    <div>
                        <div style="font-size:12px;font-weight:700;color:rgba(232,220,199,.6);margin-bottom:6px">Tanggal Mulai</div>
                        <input type="date" id="kalMulai" onchange="kalkulasi()"
                               min="{{ date('Y-m-d') }}"
                               style="width:100%;padding:9px 12px;background:rgba(255,255,255,.08);border:1px solid rgba(232,220,199,.2);border-radius:var(--r-md);color:var(--sand);font-family:var(--fb);font-size:13px;outline:none">
                    </div>
                    <div>
                        <div style="font-size:12px;font-weight:700;color:rgba(232,220,199,.6);margin-bottom:6px">Tanggal Selesai</div>
                        <input type="date" id="kalSelesai" onchange="kalkulasi()"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               style="width:100%;padding:9px 12px;background:rgba(255,255,255,.08);border:1px solid rgba(232,220,199,.2);border-radius:var(--r-md);color:var(--sand);font-family:var(--fb);font-size:13px;outline:none">
                    </div>
                </div>
                <div id="kalHasil" style="border-top:1px solid rgba(232,220,199,.12);padding-top:14px;margin-bottom:14px;display:none">
                    <div style="display:flex;justify-content:space-between;font-size:12.5px;color:rgba(232,220,199,.55);margin-bottom:6px">
                        <span id="kalDurasi">—</span>
                        <span id="kalSewa">—</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:12.5px;color:rgba(232,220,199,.55);margin-bottom:10px">
                        <span>Deposit</span>
                        <span id="kalDeposit">—</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:baseline">
                        <span style="font-size:13px;color:rgba(232,220,199,.5)">Estimasi Total</span>
                        <span id="kalTotal" style="font-family:var(--fd);font-size:22px;font-weight:700;color:var(--terracotta)">—</span>
                    </div>
                </div>
                <a id="btnBookingKalkulator" href="{{ route('sewa.index') }}" class="btn btn-primary" style="width:100%;justify-content:center">
                    Booking Sekarang
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function kalkulasi() {
    const select  = document.getElementById('kalkulasiAlat');
    const selectedOption = select.selectedOptions[0];
    const tarif   = parseFloat(select.value);
    const mulai   = document.getElementById('kalMulai').value;
    const selesai = document.getElementById('kalSelesai').value;
    const hasil   = document.getElementById('kalHasil');
    const deposit = parseFloat(selectedOption?.dataset.deposit || 0);
    const slug    = selectedOption?.dataset.slug || '';
    const btn     = document.getElementById('btnBookingKalkulator');

    // Update href button berdasarkan alat yang dipilih
    if (slug) {
        btn.href = '{{ url('/sewa-alat') }}/' + slug;
    } else {
        btn.href = '{{ route('sewa.index') }}';
    }

    if (!tarif || !mulai || !selesai) { hasil.style.display='none'; return; }

    const d1 = new Date(mulai), d2 = new Date(selesai);
    if (d2 <= d1) { hasil.style.display='none'; return; }

    const durasi = Math.floor((d2 - d1) / 86400000) + 1;
    const totalSewa  = durasi * tarif;
    const totalBayar = totalSewa + deposit;

    document.getElementById('kalDurasi').textContent  = durasi + ' hari sewa';
    document.getElementById('kalSewa').textContent    = 'Rp ' + totalSewa.toLocaleString('id-ID');
    document.getElementById('kalDeposit').textContent = 'Rp ' + deposit.toLocaleString('id-ID');
    document.getElementById('kalTotal').textContent   = 'Rp ' + totalBayar.toLocaleString('id-ID');
    hasil.style.display = 'block';
}
</script>
@endpush