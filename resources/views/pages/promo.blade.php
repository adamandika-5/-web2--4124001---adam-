@extends('layouts.app')
@section('title', 'Promo & Diskon')

@section('content')

{{-- Hero --}}
<section style="background:var(--soil);padding:48px;position:relative;overflow:hidden">
    <div style="position:absolute;inset:0;background-image:radial-gradient(ellipse 55% 70% at 80% 40%,rgba(198,107,61,.3) 0%,transparent 55%),radial-gradient(ellipse 40% 50% at 10% 80%,rgba(192,142,58,.2) 0%,transparent 55%)"></div>
    <div class="grain"></div>
    <div style="max-width:1280px;margin:0 auto;position:relative;z-index:2;text-align:center">
        <div class="section-label" style="color:var(--clay-light);justify-content:center;display:flex">Penawaran Terbaik</div>
        <h1 style="font-family:var(--fs);font-size:clamp(28px,4vw,48px);font-weight:700;color:var(--sand);margin:10px 0 12px;line-height:1.1">
            Promo & <em style="font-style:italic;color:var(--terracotta)">Diskon</em> Aktif
        </h1>
        <p style="font-size:15px;color:rgba(232,220,199,.5);max-width:480px;margin:0 auto">
            Hemat lebih banyak dengan promo pilihan Sinar Alam. Update setiap minggu!
        </p>
    </div>
</section>

{{-- Grid Promo --}}
<div style="max-width:1280px;margin:0 auto;padding:48px">

    @if($promos->isEmpty())
        <div style="text-align:center;padding:80px 40px;background:#fff;border-radius:var(--r-xl);border:1px solid rgba(176,139,110,.1)">
            <div style="font-size:56px;margin-bottom:14px">🏷️</div>
            <div style="font-family:var(--fs);font-size:22px;font-weight:700;color:var(--soil);margin-bottom:8px">Belum ada promo aktif</div>
            <div style="font-size:14px;color:var(--clay);margin-bottom:22px">Pantau terus halaman ini untuk penawaran terbaru</div>
            <a href="{{ route('katalog.index') }}" class="btn btn-primary">Belanja Tanpa Promo →</a>
        </div>
    @else
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px">
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

@endsection