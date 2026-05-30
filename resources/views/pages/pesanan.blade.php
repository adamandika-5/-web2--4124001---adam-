@extends('layouts.app')

@section('title', 'Pesanan Saya')
@section('meta_desc', 'Riwayat pesanan material bangunan Anda di Sinar Alam.')

@section('content')
<div style="max-width:1280px;margin:0 auto;padding:32px 48px 64px">

    {{-- Page Header --}}
    <div class="page-hdr">
        <div class="section-label">Akun Saya</div>
        <h1 class="section-title" style="font-size:clamp(26px,3vw,38px)">Pesanan <em>Saya</em></h1>
    </div>

    {{-- Filter Status --}}
    <form method="GET" action="{{ route('pesanan.index') }}"
          style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:24px">
        @foreach([
            '' => 'Semua Pesanan',
            'pending' => 'Menunggu',
            'dikonfirmasi' => 'Dikonfirmasi',
            'diproses' => 'Diproses',
            'dikirim' => 'Dikirim',
            'selesai' => 'Selesai',
            'batal' => 'Dibatalkan',
        ] as $val => $label)
            <a href="{{ route('pesanan.index', $val ? ['status' => $val] : []) }}"
               style="padding:6px 16px;border-radius:var(--r-xl);font-size:13px;font-weight:600;text-decoration:none;border:1.5px solid {{ request('status', '') === $val ? 'var(--terracotta)' : 'var(--sand)' }};background:{{ request('status', '') === $val ? 'var(--terracotta)' : '#fff' }};color:{{ request('status', '') === $val ? '#fff' : 'var(--clay)' }};transition:all .2s">
                {{ $label }}
            </a>
        @endforeach
    </form>

    {{-- Daftar Pesanan --}}
    @php $pesananList = $pesanans ?? collect(); @endphp

    @if($pesananList->isEmpty())
        {{-- Empty State --}}
        <div style="text-align:center;padding:72px 24px;background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08)">
            <div style="font-size:64px;margin-bottom:16px">📦</div>
            <h2 style="font-family:var(--fs);font-size:20px;font-weight:700;color:var(--soil);margin-bottom:8px">
                Belum Ada Pesanan
            </h2>
            <p style="font-size:14px;color:var(--clay);margin-bottom:24px;max-width:400px;margin-left:auto;margin-right:auto">
                Anda belum memiliki riwayat pesanan. Mulai belanja material bangunan berkualitas sekarang!
            </p>
            <a href="{{ route('katalog.index') }}" class="btn btn-primary" style="text-decoration:none">
                Jelajahi Katalog
            </a>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:16px">
            @foreach($pesananList as $pesanan)
            @php
                $statusConfig = match($pesanan->status ?? 'pending') {
                    'selesai'      => ['pill' => 's-lunas',   'label' => 'Selesai'],
                    'dikirim'      => ['pill' => 's-proses',  'label' => 'Dikirim'],
                    'diproses'     => ['pill' => 's-proses',  'label' => 'Diproses'],
                    'dikonfirmasi' => ['pill' => 's-proses',  'label' => 'Dikonfirmasi'],
                    'batal'        => ['pill' => 's-batal',   'label' => 'Dibatalkan'],
                    default        => ['pill' => 's-pending', 'label' => 'Menunggu'],
                };
                $jumlahItem = $pesanan->items_count ?? $pesanan->items?->count() ?? 0;
            @endphp

            <div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08);overflow:hidden">

                {{-- Header Kartu Pesanan --}}
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;padding:14px 20px;background:var(--oat);border-bottom:1px solid rgba(176,139,110,.1)">
                    <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap">
                        <span style="font-family:monospace;font-weight:700;color:var(--terracotta);font-size:14px">
                            {{ $pesanan->nomor_pesanan ?? '—' }}
                        </span>
                        <span style="font-size:12.5px;color:var(--clay)">
                            {{ $pesanan->created_at?->format('d M Y, H:i') ?? '—' }}
                        </span>
                        <span class="status-pill {{ $statusConfig['pill'] }}">
                            {{ $statusConfig['label'] }}
                        </span>
                    </div>
                    <div style="font-size:12.5px;color:var(--clay)">
                        {{ $jumlahItem }} produk
                    </div>
                </div>

                {{-- Preview Item Pesanan --}}
                <div style="padding:16px 20px">
                    @if($pesanan->items && $pesanan->items->count() > 0)
                        <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:16px">
                            @foreach($pesanan->items->take(3) as $item)
                            <div style="display:flex;align-items:center;gap:12px">
                                {{-- Gambar produk --}}
                                @php
                                    $gambarPath = null;
                                    // Coba gambar_utama accessor dulu (paling efisien)
                                    if ($item->produk?->gambar_utama) {
                                        $gambarPath = $item->produk->gambar_utama;
                                    } else {
                                        // Fallback: parsing gambar secara aman
                                        $gRaw = $item->produk->gambar ?? null;
                                        if ($gRaw instanceof \Illuminate\Database\Eloquent\Collection || $gRaw instanceof \Illuminate\Support\Collection) {
                                            $gambarPath = $gRaw->pluck('path')->filter()->first();
                                        } elseif (is_array($gRaw)) {
                                            $first = reset($gRaw);
                                            $gambarPath = is_object($first) ? ($first->path ?? null) : ($first ?: null);
                                        } elseif (is_string($gRaw) && !empty($gRaw)) {
                                            $dec = json_decode($gRaw, true);
                                            $gambarPath = (json_last_error() === JSON_ERROR_NONE && is_array($dec)) ? ($dec[0] ?? null) : $gRaw;
                                        }
                                    }
                                @endphp
                                <div style="width:48px;height:48px;border-radius:var(--r-sm);background:var(--oat);display:flex;align-items:center;justify-content:center;font-size:20px;overflow:hidden;flex-shrink:0;border:1px solid rgba(176,139,110,.1)">
                                    @if($gambarPath)
                                        <img src="{{ asset('storage/' . $gambarPath) }}" style="width:100%;height:100%;object-fit:cover" alt="{{ $item->nama_produk ?? '' }}">
                                    @else
                                        📦
                                    @endif
                                </div>
                                <div style="flex:1;min-width:0">
                                    <div style="font-size:13.5px;font-weight:600;color:var(--soil);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                        {{ $item->nama_produk ?? $item->produk?->nama ?? 'Produk' }}
                                    </div>
                                    <div style="font-size:12px;color:var(--clay)">
                                        {{ $item->qty ?? 1 }} {{ $item->satuan ?? '' }} × Rp {{ number_format($item->harga_promo ?? $item->harga_satuan ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            @if($pesanan->items->count() > 3)
                                <div style="font-size:12.5px;color:var(--clay);font-style:italic">
                                    +{{ $pesanan->items->count() - 3 }} produk lainnya...
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Total & Tombol Aksi --}}
                    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;padding-top:14px;border-top:1px solid var(--sand)">
                        <div>
                            <div style="font-size:12px;color:var(--clay);margin-bottom:2px">Total Pembayaran</div>
                            <div style="font-family:var(--fd);font-size:20px;font-weight:700;color:var(--terracotta)">
                                Rp {{ number_format($pesanan->total ?? 0, 0, ',', '.') }}
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div style="display:flex;gap:8px;flex-wrap:wrap">
                            {{-- Tombol Detail --}}
                            <a href="{{ route('pesanan.show', $pesanan->nomor_pesanan) }}"
                               class="btn btn-primary btn-sm"
                               style="text-decoration:none">
                                Lihat Detail
                            </a>

                            {{-- Tombol Invoice (hanya jika pesanan selesai) --}}
                            @if(($pesanan->status ?? '') === 'selesai')
                                <a href="{{ route('pesanan.invoice', $pesanan->nomor_pesanan) }}"
                                   class="btn btn-secondary btn-sm"
                                   style="text-decoration:none">
                                    📄 Invoice
                                </a>
                            @endif

                            {{-- Tombol Batal (hanya jika pending) --}}
                            @if(($pesanan->status ?? '') === 'pending')
                                <form action="{{ route('pesanan.batal', $pesanan->nomor_pesanan) }}"
                                      method="POST"
                                      onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm"
                                            style="background:rgba(192,48,48,.08);color:#c03030;border:1.5px solid rgba(192,48,48,.2)">
                                        Batalkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if(method_exists($pesananList, 'links'))
            <div style="margin-top:32px;display:flex;justify-content:center">
                {{ $pesananList->links() }}
            </div>
        @endif
    @endif

</div>
@endsection
