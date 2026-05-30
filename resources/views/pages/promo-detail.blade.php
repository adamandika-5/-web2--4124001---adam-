@extends('layouts.app')
@section('title', $promo->nama . ' — Promo Sinar Alam')

@section('content')
<div style="max-width:1100px;margin:0 auto;padding:36px 48px 64px">

    {{-- Breadcrumb --}}
    <nav style="font-size:13px;color:var(--clay);display:flex;align-items:center;gap:7px;margin-bottom:28px;flex-wrap:wrap">
        <a href="{{ route('beranda') }}" style="color:var(--terracotta);text-decoration:none;font-weight:600">Beranda</a>
        <span>›</span>
        <a href="{{ route('promo') }}" style="color:var(--terracotta);text-decoration:none;font-weight:600">Promo</a>
        <span>›</span>
        <span>{{ $promo->nama }}</span>
    </nav>

    {{-- Hero Promo --}}
    <div style="background:var(--soil);border-radius:var(--r-xl);padding:48px;position:relative;overflow:hidden;margin-bottom:36px">
        <div style="position:absolute;inset:0;background-image:radial-gradient(ellipse 60% 70% at 80% 30%,rgba(198,107,61,.3) 0%,transparent 55%),radial-gradient(ellipse 40% 50% at 10% 80%,rgba(192,142,58,.18) 0%,transparent 50%)"></div>
        <div class="grain"></div>
        <div style="position:relative;z-index:2;display:grid;grid-template-columns:2fr 1fr;gap:40px;align-items:center">
            <div>
                @if($promo->label)
                    <div style="display:inline-flex;align-items:center;gap:8px;padding:5px 14px;background:rgba(198,107,61,.2);border:1px solid rgba(198,107,61,.4);border-radius:20px;color:var(--clay-light);font-size:11.5px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;margin-bottom:18px">
                        <div style="width:6px;height:6px;background:var(--terracotta);border-radius:50%;animation:pulse 2s ease-in-out infinite"></div>
                        {{ $promo->label }}
                    </div>
                @endif
                <h1 style="font-family:var(--fs);font-size:clamp(28px,3.5vw,44px);font-weight:700;color:var(--sand);line-height:1.1;margin-bottom:14px">
                    @if($promo->judul_html)
                        {!! $promo->judul_html !!}
                    @else
                        {{ $promo->nama }}
                    @endif
                </h1>
                @if($promo->deskripsi)
                    <p style="font-size:15px;color:rgba(232,220,199,.55);line-height:1.75;max-width:480px;margin-bottom:24px">
                        {{ $promo->deskripsi }}
                    </p>
                @endif
                <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
                    <a href="{{ route('katalog.index') }}" class="btn btn-primary" style="font-size:15px;padding:12px 28px">
                        🛒 Belanja Sekarang
                    </a>
                    <div style="font-size:13px;color:rgba(232,220,199,.4)">
                        Berakhir {{ $promo->berakhir_at->isoFormat('D MMMM Y') }}
                    </div>
                </div>
            </div>

            {{-- Badge diskon besar --}}
            <div style="text-align:center">
                <div style="width:160px;height:160px;border-radius:50%;background:rgba(198,107,61,.15);border:2px solid rgba(198,107,61,.4);display:flex;flex-direction:column;align-items:center;justify-content:center;margin:0 auto">
                    <div style="font-family:var(--fs);font-size:44px;font-weight:700;color:var(--terracotta);line-height:1">
                        @if($promo->tipe === 'persentase')
                            {{ $promo->nilai }}%
                        @elseif($promo->tipe === 'nominal')
                            Rp<br>{{ number_format($promo->nilai/1000, 0) }}K
                        @else
                            FREE<br>ONGKIR
                        @endif
                    </div>
                    <div style="font-size:12px;color:rgba(232,220,199,.5);margin-top:6px;font-weight:600">DISKON</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Syarat & Ketentuan --}}
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;margin-bottom:36px">
        <div style="background:#fff;border-radius:var(--r-lg);padding:28px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08)">
            <div style="font-family:var(--fs);font-size:18px;font-weight:700;color:var(--soil);margin-bottom:18px;padding-bottom:14px;border-bottom:1px solid var(--sand)">
                Syarat & Ketentuan Promo
            </div>
            <div style="display:flex;flex-direction:column;gap:12px">
                @if($promo->min_belanja > 0)
                <div style="display:flex;align-items:flex-start;gap:10px;font-size:14px;color:var(--soil-light)">
                    <span style="color:var(--moss);font-size:16px;flex-shrink:0">✓</span>
                    Minimum pembelian <strong>Rp {{ number_format($promo->min_belanja, 0, ',', '.') }}</strong>
                </div>
                @endif
                @if($promo->maks_diskon)
                <div style="display:flex;align-items:flex-start;gap:10px;font-size:14px;color:var(--soil-light)">
                    <span style="color:var(--moss);font-size:16px;flex-shrink:0">✓</span>
                    Maksimal diskon <strong>Rp {{ number_format($promo->maks_diskon, 0, ',', '.') }}</strong>
                </div>
                @endif
                <div style="display:flex;align-items:flex-start;gap:10px;font-size:14px;color:var(--soil-light)">
                    <span style="color:var(--moss);font-size:16px;flex-shrink:0">✓</span>
                    Berlaku mulai <strong>{{ $promo->mulai_at->isoFormat('D MMMM Y') }}</strong>
                    sampai <strong>{{ $promo->berakhir_at->isoFormat('D MMMM Y') }}</strong>
                </div>
                <div style="display:flex;align-items:flex-start;gap:10px;font-size:14px;color:var(--soil-light)">
                    <span style="color:var(--moss);font-size:16px;flex-shrink:0">✓</span>
                    Promo dapat digabungkan dengan kode voucher
                </div>
                <div style="display:flex;align-items:flex-start;gap:10px;font-size:14px;color:var(--soil-light)">
                    <span style="color:var(--moss);font-size:16px;flex-shrink:0">✓</span>
                    Berlaku untuk semua metode pembayaran
                </div>
                <div style="display:flex;align-items:flex-start;gap:10px;font-size:14px;color:var(--soil-light)">
                    <span style="color:var(--ochre);font-size:16px;flex-shrink:0">⚠</span>
                    Promo dapat berakhir sewaktu-waktu tanpa pemberitahuan
                </div>
            </div>
        </div>

        {{-- Countdown & CTA --}}
        <div style="display:flex;flex-direction:column;gap:14px">
            <div style="background:var(--soil);border-radius:var(--r-lg);padding:22px;text-align:center">
                <div class="grain"></div>
                <div style="position:relative;z-index:2">
                    <div style="font-size:12px;color:rgba(232,220,199,.45);font-weight:700;text-transform:uppercase;letter-spacing:.07em;margin-bottom:12px">
                        Berakhir dalam
                    </div>
                    @php
                        $diff = now()->diff($promo->berakhir_at);
                        $hari = $diff->days;
                        $jam  = $diff->h;
                    @endphp
                    <div style="display:flex;gap:8px;justify-content:center;margin-bottom:14px">
                        @foreach([[$hari, 'Hari'], [$jam, 'Jam']] as [$val, $lbl])
                        <div style="background:rgba(255,255,255,.07);border-radius:var(--r-sm);padding:10px 14px;min-width:56px">
                            <div style="font-family:var(--fs);font-size:28px;font-weight:700;color:var(--sand)">{{ str_pad($val, 2, '0', STR_PAD_LEFT) }}</div>
                            <div style="font-size:10px;color:rgba(232,220,199,.4);margin-top:3px;font-weight:700;text-transform:uppercase">{{ $lbl }}</div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('katalog.index') }}" class="btn btn-primary" style="width:100%;justify-content:center;font-size:14px">
                        Manfaatkan Sekarang
                    </a>
                </div>
            </div>
            <a href="{{ route('promo') }}"
               style="display:block;text-align:center;padding:14px;background:#fff;border-radius:var(--r-lg);font-size:13px;color:var(--terracotta);font-weight:600;text-decoration:none;border:1px solid rgba(176,139,110,.08);box-shadow:var(--sh-sm)"
               onmouseover="this.style.background='var(--oat)'" onmouseout="this.style.background='#fff'">
                ← Lihat Promo Lainnya
            </a>
        </div>
    </div>
</div>
@endsection