@extends('layouts.app')
@section('title', 'Lacak Pesanan')

@section('content')
<div style="max-width:780px;margin:0 auto;padding:52px 48px">

    {{-- Header --}}
    <div style="text-align:center;margin-bottom:40px">
        <div class="section-label" style="justify-content:center;display:flex">Cek Status</div>
        <h1 class="section-title" style="text-align:center">Lacak <em>Pesanan</em> Anda</h1>
        <p style="font-size:14.5px;color:var(--clay);margin-top:10px">
            Masukkan nomor pesanan yang Anda terima via email atau WhatsApp
        </p>
    </div>

    {{-- Form Lacak --}}
    <form action="{{ route('lacak') }}" method="GET"
          onsubmit="event.preventDefault(); const nomor = document.getElementById('nomorInput').value.trim(); if (nomor) { window.location.href = '{{ url('/lacak') }}/' + encodeURIComponent(nomor); }"
          style="background:#fff;border-radius:var(--r-xl);padding:32px;box-shadow:var(--sh-md);border:1px solid rgba(176,139,110,.1);margin-bottom:36px">
        <label class="form-lbl" style="font-size:14px;margin-bottom:10px;display:block">
            Nomor Pesanan
        </label>
        <div style="display:flex;gap:10px">
            <input type="text" id="nomorInput"
                   value="{{ request('nomor') ?? (isset($pesanan) ? $pesanan->nomor_pesanan : '') }}"
                   placeholder="Contoh: SA-20250518-0042"
                   class="form-inp"
                   style="flex:1;font-size:15px;padding:12px 16px;font-family:monospace;letter-spacing:.05em">
            <button type="submit" class="btn btn-primary" style="padding:12px 28px;font-size:15px">
                🔍 Lacak
            </button>
        </div>
        <div style="font-size:12px;color:var(--clay);margin-top:10px">
            Nomor pesanan dikirim ke email dan WhatsApp saat pesanan berhasil dibuat
        </div>
    </form>

    {{-- Hasil Lacak --}}
    @isset($pesanan)
    <div style="background:#fff;border-radius:var(--r-xl);box-shadow:var(--sh-md);border:1px solid rgba(176,139,110,.1);overflow:hidden">

        {{-- Header pesanan --}}
        <div style="background:var(--soil);padding:24px 28px;position:relative;overflow:hidden">
            <div class="grain"></div>
            <div style="position:absolute;inset:0;background:radial-gradient(ellipse 60% 70% at 80% 30%,rgba(198,107,61,.25) 0%,transparent 55%)"></div>
            <div style="position:relative;z-index:2;display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px">
                <div>
                    <div style="font-size:11px;color:rgba(232,220,199,.5);font-weight:700;letter-spacing:.07em;text-transform:uppercase;margin-bottom:4px">Nomor Pesanan</div>
                    <div style="font-family:var(--fs);font-size:22px;font-weight:700;color:var(--sand)">{{ $pesanan->nomor_pesanan }}</div>
                    <div style="font-size:13px;color:rgba(232,220,199,.5);margin-top:4px">
                        {{ $pesanan->created_at->isoFormat('dddd, D MMMM Y · HH:mm') }} WIB
                    </div>
                </div>
                @php $statusClass = match($pesanan->status) {
                    'selesai'=>'s-lunas','diproses'=>'s-proses','dikirim'=>'s-proses',
                    'pending'=>'s-pending','batal'=>'s-batal',default=>'s-pending'
                }; @endphp
                <span class="status-pill {{ $statusClass }}" style="font-size:13px;padding:6px 16px">
                    {{ match($pesanan->status) {
                        'selesai'=>'✓ Selesai','diproses'=>'⚙ Diproses','dikirim'=>'🚚 Dikirim',
                        'dikonfirmasi'=>'✓ Dikonfirmasi','pending'=>'⏳ Menunggu','batal'=>'✕ Dibatalkan',
                        default=>ucfirst($pesanan->status)
                    } }}
                </span>
            </div>
        </div>

        {{-- Timeline progress --}}
        <div style="padding:28px;border-bottom:1px solid rgba(176,139,110,.1)">
            @php
                $steps = [
                    ['key'=>'pending','label'=>'Pesanan Masuk','icon'=>'📋','desc'=>'Pesanan diterima dan menunggu konfirmasi'],
                    ['key'=>'dikonfirmasi','label'=>'Dikonfirmasi','icon'=>'✅','desc'=>'Pembayaran atau pesanan telah dikonfirmasi'],
                    ['key'=>'diproses','label'=>'Diproses','icon'=>'⚙️','desc'=>'Pesanan sedang disiapkan di gudang'],
                    ['key'=>'dikirim','label'=>'Dikirim','icon'=>'🚚','desc'=>'Pesanan dalam perjalanan ke alamat Anda'],
                    ['key'=>'selesai','label'=>'Selesai','icon'=>'🎉','desc'=>'Pesanan telah sampai di tujuan'],
                ];
                $statusOrder = ['pending','dikonfirmasi','diproses','dikirim','selesai'];
                $currentIdx = array_search($pesanan->status, $statusOrder);
                if ($currentIdx === false) $currentIdx = -1;
            @endphp

            <div style="display:flex;align-items:flex-start;gap:0;position:relative">
                @foreach($steps as $i => $step)
                @php
                    $stepIdx = array_search($step['key'], $statusOrder);
                    $isDone = $stepIdx !== false && $currentIdx >= $stepIdx && $pesanan->status !== 'batal';
                    $isCurrent = $step['key'] === $pesanan->status;
                @endphp
                <div style="flex:1;display:flex;flex-direction:column;align-items:center;position:relative">

                    {{-- Garis penghubung kiri --}}
                    @if($i > 0)
                    <div style="position:absolute;top:20px;right:50%;width:100%;height:2px;
                          background:{{ $isDone ? 'var(--terracotta)' : 'var(--sand)' }};
                          z-index:0"></div>
                    @endif

                    {{-- Ikon step --}}
                    <div style="width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:18px;position:relative;z-index:1;flex-shrink:0;transition:all .3s;
                          background:{{ $isCurrent ? 'var(--terracotta)' : ($isDone ? 'var(--moss)' : 'var(--sand)') }};
                          border:2px solid {{ $isCurrent ? 'var(--terra-dark)' : ($isDone ? 'var(--moss)' : 'rgba(176,139,110,.3)') }};
                          box-shadow:{{ $isCurrent ? '0 0 0 4px rgba(198,107,61,.2)' : 'none' }}">
                        {{ $isDone && !$isCurrent ? '✓' : $step['icon'] }}
                    </div>

                    {{-- Label --}}
                    <div style="margin-top:10px;text-align:center;padding:0 4px">
                        <div style="font-size:12px;font-weight:700;color:{{ $isDone || $isCurrent ? 'var(--soil)' : 'var(--concrete)' }}">
                            {{ $step['label'] }}
                        </div>
                        @if($isCurrent)
                        <div style="font-size:10.5px;color:var(--terracotta);margin-top:3px;line-height:1.4">{{ $step['desc'] }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            @if($pesanan->status === 'batal')
            <div style="margin-top:20px;padding:14px 18px;background:rgba(192,48,48,.07);border-radius:var(--r-md);border-left:3px solid #c03030;font-size:13.5px;color:#c03030;font-weight:600">
                ✕ Pesanan ini telah dibatalkan
                @if($pesanan->catatan_admin)
                    — {{ $pesanan->catatan_admin }}
                @endif
            </div>
            @endif
        </div>

        {{-- Detail pesanan --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">

            {{-- Produk --}}
            <div style="padding:22px 28px;border-right:1px solid rgba(176,139,110,.1)">
                <div style="font-size:13px;font-weight:700;color:var(--clay);text-transform:uppercase;letter-spacing:.05em;margin-bottom:14px">
                    Produk Dipesan
                </div>
                {{--
                  Eager loading di controller:
                  Pesanan::with(['items.produk.gambar'])->where('nomor_pesanan', $nomor)->firstOrFail()
                --}}
                @foreach($pesanan->items as $item)
                <div style="display:flex;gap:10px;align-items:center;margin-bottom:10px">
                    <div style="width:40px;height:40px;background:var(--oat);border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;overflow:hidden">
                        @if($item->produk)
                            @include('partials.produk-img', ['produk' => $item->produk])
                        @else
                            📦
                        @endif
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:13px;font-weight:600;color:var(--soil);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                            {{ $item->nama_produk }}
                        </div>
                        <div style="font-size:11.5px;color:var(--clay)">
                            {{ $item->qty }} {{ $item->satuan }} · Rp {{ number_format($item->harga_promo ?? $item->harga_satuan, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                @endforeach

                <div style="border-top:1px solid var(--sand);padding-top:12px;margin-top:4px">
                    <div style="display:flex;justify-content:space-between;font-size:13.5px">
                        <span style="color:var(--clay)">Total Pembayaran</span>
                        <span style="font-family:var(--fs);font-size:17px;font-weight:700;color:var(--terracotta)">
                            Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Info Pengiriman --}}
            <div style="padding:22px 28px">
                <div style="font-size:13px;font-weight:700;color:var(--clay);text-transform:uppercase;letter-spacing:.05em;margin-bottom:14px">
                    Info Pengiriman
                </div>
                <div style="display:flex;flex-direction:column;gap:10px">
                    <div>
                        <div style="font-size:11.5px;color:var(--clay);margin-bottom:2px">Penerima</div>
                        <div style="font-size:13.5px;font-weight:700;color:var(--soil)">{{ $pesanan->penerima }}</div>
                        <div style="font-size:12.5px;color:var(--clay)">📞 {{ $pesanan->telepon_penerima }}</div>
                    </div>
                    <div>
                        <div style="font-size:11.5px;color:var(--clay);margin-bottom:2px">Alamat</div>
                        <div style="font-size:13px;color:var(--soil-light);line-height:1.55">
                            {{ $pesanan->alamat_pengiriman }},<br>
                            {{ $pesanan->kota_tujuan }}, {{ $pesanan->provinsi_tujuan }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size:11.5px;color:var(--clay);margin-bottom:2px">Metode Kirim</div>
                        <div style="font-size:13.5px;font-weight:700;color:var(--soil)">
                            {{ $pesanan->jenis_pengiriman === 'armada' ? '🚛 Armada Sendiri Sinar Alam' : '📦 '.strtoupper($pesanan->ekspedisi ?? 'Ekspedisi') }}
                        </div>
                    </div>
                    @if($pesanan->dikirim_at)
                    <div>
                        <div style="font-size:11.5px;color:var(--clay);margin-bottom:2px">Tanggal Dikirim</div>
                        <div style="font-size:13.5px;font-weight:700;color:var(--moss)">
                            {{ $pesanan->dikirim_at->isoFormat('D MMMM Y') }}
                        </div>
                    </div>
                    @endif
                    @if($pesanan->selesai_at)
                    <div>
                        <div style="font-size:11.5px;color:var(--clay);margin-bottom:2px">Tanggal Diterima</div>
                        <div style="font-size:13.5px;font-weight:700;color:var(--moss)">
                            {{ $pesanan->selesai_at->isoFormat('D MMMM Y') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Footer aksi --}}
        <div style="padding:18px 28px;background:var(--oat);border-top:1px solid rgba(176,139,110,.1);display:flex;gap:10px;flex-wrap:wrap">
            @auth
                <a href="{{ route('pesanan.show', $pesanan->nomor_pesanan) }}" class="btn btn-primary btn-sm">
                    Detail Lengkap →
                </a>
                <a href="{{ route('pesanan.invoice', $pesanan->nomor_pesanan) }}" target="_blank" class="btn btn-secondary btn-sm">
                    📄 Download Invoice
                </a>
            @endauth
            <a href="https://wa.me/{{ config('app.whatsapp_number') }}?text=Halo%20Sinar%20Alam%2C%20saya%20ingin%20tanya%20pesanan%20{{ urlencode($pesanan->nomor_pesanan) }}"
               target="_blank" class="btn btn-secondary btn-sm">
                💬 Tanya via WhatsApp
            </a>
        </div>
    </div>

    @elseif(request()->has('nomor') || isset($notFound))
    <div style="text-align:center;padding:48px;background:#fff;border-radius:var(--r-xl);border:1px solid rgba(176,139,110,.1)">
        <div style="font-size:48px;margin-bottom:14px">🔍</div>
        <div style="font-family:var(--fs);font-size:20px;font-weight:700;color:var(--soil);margin-bottom:8px">Pesanan tidak ditemukan</div>
        <div style="font-size:14px;color:var(--clay)">Pastikan nomor pesanan yang Anda masukkan sudah benar</div>
    </div>
    @endisset

    {{-- Tips --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:36px">
        @foreach([
            ['📧','Cek Email','Nomor pesanan dikirim ke email Anda setelah checkout'],
            ['💬','Hubungi Kami','Tim kami siap membantu di WhatsApp setiap hari'],
            ['👤','Login Akun','Login untuk melihat semua riwayat pesanan Anda'],
        ] as [$ikon,$judul,$desc])
        <div style="background:#fff;border-radius:var(--r-lg);padding:20px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08);text-align:center">
            <div style="font-size:28px;margin-bottom:10px">{{ $ikon }}</div>
            <div style="font-size:14px;font-weight:700;color:var(--soil);margin-bottom:5px">{{ $judul }}</div>
            <div style="font-size:12.5px;color:var(--clay);line-height:1.55">{{ $desc }}</div>
        </div>
        @endforeach
    </div>
</div>
@endsection