@extends('layouts.app')
@section('title', 'Promo & Diskon')

@section('content')

{{-- Hero --}}
<section class="promo-hero-section">
    <div style="position:absolute;inset:0;background-image:radial-gradient(ellipse 55% 70% at 80% 40%,rgba(198,107,61,.3) 0%,transparent 55%),radial-gradient(ellipse 40% 50% at 10% 80%,rgba(192,142,58,.2) 0%,transparent 55%)"></div>
    <div class="grain"></div>
    <div style="max-width:1280px;margin:0 auto;position:relative;z-index:2;text-align:center">
        <div class="section-label" style="color:var(--clay-light);justify-content:center;display:flex">Penawaran Terbaik</div>
        <h1 style="font-family:var(--fs);font-size:clamp(28px,4vw,48px);font-weight:700;color:var(--sand);margin:10px 0 12px;line-height:1.1">
            Promo &amp; <em style="font-style:italic;color:var(--terracotta)">Diskon</em> Aktif
        </h1>
        <p style="font-size:15px;color:rgba(232,220,199,.5);max-width:480px;margin:0 auto">
            Hemat lebih banyak dengan promo pilihan Sinar Alam. Update setiap minggu!
        </p>
    </div>
</section>

{{-- Grid Promo --}}
<div class="promo-grid-section">

    @if($promos->isEmpty())
        <div style="text-align:center;padding:80px 40px;background:#fff;border-radius:var(--r-xl);border:1px solid rgba(176,139,110,.1)">
            <div style="font-size:56px;margin-bottom:14px">🏷️</div>
            <div style="font-family:var(--fs);font-size:22px;font-weight:700;color:var(--soil);margin-bottom:8px">Belum ada promo aktif</div>
            <div style="font-size:14px;color:var(--clay);margin-bottom:22px">Pantau terus halaman ini untuk penawaran terbaru</div>
            <a href="{{ route('katalog.index') }}" class="btn btn-primary">Belanja Tanpa Promo →</a>
        </div>
    @else
        <div class="promo-grid">
            @foreach($promos as $promo)
            <a href="{{ route('promo.show', $promo->slug) }}"
               style="text-decoration:none;display:block;background:var(--soil-mid);border-radius:var(--r-xl);padding:32px;position:relative;overflow:hidden;transition:transform .3s var(--ease)"
               onmouseover="this.style.transform='translateY(-4px)'"
               onmouseout="this.style.transform='translateY(0)'">
                <div class="grain"></div>
                <div style="position:absolute;top:-20%;right:-10%;font-size:100px;opacity:.08;pointer-events:none">🏷️</div>
                <div style="position:relative;z-index:2">
                    @if($promo->label)
                        <div style="font-size:11px;font-weight:700;color:rgba(232,220,199,.5);letter-spacing:.1em;text-transform:uppercase;margin-bottom:8px">
                            {{ $promo->label }}
                        </div>
                    @endif
                    <div style="font-family:var(--fs);font-size:22px;font-weight:700;color:var(--sand);line-height:1.3;margin-bottom:14px">
                        @if($promo->judul_html)
                            {!! $promo->judul_html !!}
                        @else
                            {{ $promo->nama }}
                        @endif
                    </div>
                    @if($promo->deskripsi)
                        <div style="font-size:13px;color:rgba(232,220,199,.45);margin-bottom:14px;line-height:1.6">
                            {{ Str::limit($promo->deskripsi, 80) }}
                        </div>
                    @endif

                    {{-- Nilai diskon --}}
                    <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(198,107,61,.2);border:1px solid rgba(198,107,61,.4);border-radius:var(--r-xl);padding:6px 14px;margin-bottom:16px">
                        <span style="font-family:var(--fd);font-size:18px;font-weight:700;color:var(--terracotta)">
                            @if($promo->tipe === 'persentase')
                                {{ $promo->nilai }}%
                            @elseif($promo->tipe === 'nominal')
                                Rp {{ number_format($promo->nilai, 0, ',', '.') }}
                            @else
                                Gratis Ongkir
                            @endif
                        </span>
                        <span style="font-size:12px;color:rgba(232,220,199,.5)">diskon</span>
                    </div>

                    <div style="display:flex;align-items:center;justify-content:space-between">
                        <div style="font-size:12px;color:rgba(232,220,199,.4)">
                            Berakhir {{ $promo->berakhir_at->isoFormat('D MMM Y') }}
                        </div>
                        <div style="font-size:13px;font-weight:700;color:var(--terracotta)">
                            Lihat Promo →
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($promos->hasPages())
        <div style="display:flex;justify-content:center;gap:6px;margin-top:36px">
            @if(!$promos->onFirstPage())<a href="{{ $promos->previousPageUrl() }}" class="pag-btn">‹</a>@endif
            @foreach($promos->getUrlRange(max(1,$promos->currentPage()-2),min($promos->lastPage(),$promos->currentPage()+2)) as $p=>$u)
                @if($p==$promos->currentPage())<button class="pag-btn active">{{$p}}</button>
                @else<a href="{{ $u }}" class="pag-btn">{{$p}}</a>@endif
            @endforeach
            @if($promos->hasMorePages())<a href="{{ $promos->nextPageUrl() }}" class="pag-btn">›</a>@endif
        </div>
        @endif
    @endif
</div>

{{-- ── SECTION KODE VOUCHER ── --}}
<div class="promo-voucher-section">
<div class="promo-voucher-inner">

    <div style="text-align:center;margin-bottom:36px">
        <div class="section-label" style="justify-content:center;display:flex">Kode Voucher</div>
        <h2 style="font-family:var(--fs);font-size:clamp(22px,3vw,36px);font-weight:700;color:var(--soil);margin:8px 0 10px">
            🎫 Voucher Diskon Aktif
        </h2>
        <p style="font-size:14px;color:var(--clay);max-width:480px;margin:0 auto">
            Gunakan kode voucher berikut saat checkout untuk mendapatkan potongan harga.
        </p>
    </div>

    @if($vouchers->isEmpty())
        <div style="text-align:center;padding:48px 32px;background:#fff;border-radius:var(--r-xl);border:1px solid rgba(176,139,110,.1)">
            <div style="font-size:48px;margin-bottom:12px">🎟️</div>
            <div style="font-size:16px;font-weight:600;color:var(--soil);margin-bottom:6px">Belum ada kode voucher aktif saat ini</div>
            <div style="font-size:13px;color:var(--clay)">Pantau terus halaman ini untuk kode voucher terbaru</div>
        </div>
    @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:18px">
            @foreach($vouchers as $v)
            <div style="background:#fff;border-radius:var(--r-xl);overflow:hidden;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.1);display:flex;flex-direction:column">

                {{-- Header --}}
                <div style="background:var(--soil);padding:20px 22px;position:relative;overflow:hidden">
                    <div class="grain" style="opacity:.4"></div>
                    <div style="position:absolute;top:-10px;right:-10px;font-size:72px;opacity:.06">🎟️</div>
                    <div style="position:relative;z-index:2">
                        <div style="font-size:11px;font-weight:700;color:rgba(232,220,199,.5);letter-spacing:.08em;text-transform:uppercase;margin-bottom:6px">
                            {{ $v->tipe === 'persentase' ? 'Diskon Persentase' : ($v->tipe === 'gratis_ongkir' ? 'Gratis Ongkir' : 'Diskon Nominal') }}
                        </div>
                        <div style="font-family:var(--fs);font-size:20px;font-weight:700;color:var(--sand);margin-bottom:4px">{{ $v->nama }}</div>
                        <div style="font-size:22px;font-weight:900;color:var(--terracotta)">
                            @if($v->tipe === 'persentase')
                                {{ rtrim(rtrim(number_format($v->nilai, 2, '.', ''), '0'), '.') }}%
                            @elseif($v->tipe === 'gratis_ongkir')
                                Gratis Ongkir
                            @else
                                Rp {{ number_format($v->nilai, 0, ',', '.') }}
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Body --}}
                <div style="padding:18px 22px;flex:1;display:flex;flex-direction:column;gap:10px">
                    {{-- Kode salin --}}
                    <div style="display:flex;align-items:center;gap:8px;background:var(--oat);border:1.5px dashed var(--sand);border-radius:var(--r-md);padding:10px 14px">
                        <code id="kode-{{ $v->id }}"
                              style="flex:1;font-size:15px;font-weight:800;color:var(--soil);letter-spacing:.1em;font-family:monospace">{{ $v->kode }}</code>
                        <button onclick="salинKode('{{ $v->kode }}','{{ $v->id }}')"
                                style="flex-shrink:0;background:var(--terracotta);color:#fff;border:none;border-radius:var(--r-sm);padding:5px 12px;font-size:12px;font-weight:700;cursor:pointer;font-family:var(--fb);transition:background .2s"
                                id="btn-salin-{{ $v->id }}"
                                onmouseover="this.style.background='var(--soil)'"
                                onmouseout="this.style.background='var(--terracotta)'">
                            📋 Salin
                        </button>
                    </div>

                    {{-- Detail --}}
                    <div style="display:flex;flex-direction:column;gap:5px;font-size:12.5px;color:var(--clay)">
                        @if($v->min_belanja && $v->min_belanja > 0)
                        <div style="display:flex;justify-content:space-between">
                            <span>Min. Belanja</span>
                            <span style="font-weight:600;color:var(--soil)">Rp {{ number_format($v->min_belanja, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($v->maks_diskon && $v->maks_diskon > 0)
                        <div style="display:flex;justify-content:space-between">
                            <span>Maks. Diskon</span>
                            <span style="font-weight:600;color:var(--soil)">Rp {{ number_format($v->maks_diskon, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($v->kuota)
                        <div style="display:flex;justify-content:space-between">
                            <span>Sisa Kuota</span>
                            <span style="font-weight:600;color:{{ ($v->kuota - $v->terpakai) <= 5 ? '#c03030' : 'var(--moss)' }}">
                                {{ $v->kuota - $v->terpakai }} tersisa
                            </span>
                        </div>
                        @endif
                        @if($v->berlaku_sampai)
                        <div style="display:flex;justify-content:space-between">
                            <span>Berlaku s/d</span>
                            <span style="font-weight:600;color:var(--soil)">{{ $v->berlaku_sampai->format('d M Y') }}</span>
                        </div>
                        @else
                        <div style="display:flex;justify-content:space-between">
                            <span>Berlaku</span>
                            <span style="font-weight:600;color:var(--moss)">Tidak terbatas</span>
                        </div>
                        @endif
                    </div>

                    {{-- CTA --}}
                    <a href="{{ route('katalog.index') }}"
                       style="margin-top:auto;display:block;text-align:center;padding:10px;background:var(--soil);color:var(--sand);border-radius:var(--r-md);font-size:13px;font-weight:700;text-decoration:none;transition:background .2s"
                       onmouseover="this.style.background='var(--terracotta)'"
                       onmouseout="this.style.background='var(--soil)'">
                        Belanja Sekarang →
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <p style="text-align:center;margin-top:28px;font-size:13px;color:var(--clay)">
            💡 Gunakan kode voucher di halaman <strong>Checkout</strong> pada kolom "Kode Voucher".
        </p>
    @endif

</div>
</div>

@push('scripts')
<script>
function salинKode(kode, id) {
    navigator.clipboard.writeText(kode).then(() => {
        const btn = document.getElementById('btn-salin-' + id);
        const ori = btn.textContent;
        btn.textContent = '✅ Tersalin!';
        btn.style.background = 'var(--moss)';
        setTimeout(() => {
            btn.textContent = ori;
            btn.style.background = 'var(--terracotta)';
        }, 2000);
    }).catch(() => {
        // Fallback: select text
        const el = document.getElementById('kode-' + id);
        const range = document.createRange();
        range.selectNode(el);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
    });
}
</script>
@endpush

@endsection