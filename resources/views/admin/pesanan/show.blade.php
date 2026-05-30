@extends('layouts.admin')
@section('title', 'Detail Pesanan')
@section('page_title', 'Detail Pesanan')
@section('breadcrumb', 'Utama › Pesanan › ' . $pesanan->nomor_pesanan)

@section('content')
{{--
  Eager loading di controller:
  Pesanan::with([
      'user.alamat',
      'items.produk.gambar',
      'pembayaran.dikonfirmasiOleh',
      'voucher'
  ])->findOrFail($id)
--}}

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">

    {{-- ── KOLOM KIRI ── --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- Info pesanan --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:24px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--sand)">
                <div>
                    <div style="font-size:11px;color:var(--clay);font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px">Nomor Pesanan</div>
                    <div style="font-family:var(--fd);font-size:22px;font-weight:700;color:var(--soil)">{{ $pesanan->nomor_pesanan }}</div>
                    <div style="font-size:13px;color:var(--clay);margin-top:4px">{{ $pesanan->created_at->isoFormat('dddd, D MMMM Y · HH:mm') }} WIB</div>
                </div>
                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px">
                    @php $statusClass = match($pesanan->status) {
                        'selesai'=>'s-lunas','diproses'=>'s-proses','dikirim'=>'s-proses',
                        'pending'=>'s-pending','batal'=>'s-batal',default=>'s-pending'
                    }; @endphp
                    <span class="status-pill {{ $statusClass }}" style="font-size:13px;padding:5px 14px">
                        {{ ucfirst($pesanan->status) }}
                    </span>
                    <a href="{{ route('pesanan.invoice', $pesanan->nomor_pesanan) }}" target="_blank"
                       class="btn btn-secondary btn-sm">📄 Invoice PDF</a>
                </div>
            </div>

            {{-- Update status --}}
            <form action="{{ route('admin.pesanan.status', $pesanan->id) }}" method="POST" style="display:flex;gap:10px;flex-wrap:wrap">
                @csrf @method('PATCH')
                <select name="status" class="form-inp" style="flex:1;padding:8px 12px;font-size:13px">
                    @foreach(['pending'=>'Pending','dikonfirmasi'=>'Dikonfirmasi','diproses'=>'Diproses','dikirim'=>'Dikirim','selesai'=>'Selesai','batal'=>'Dibatalkan'] as $val=>$lbl)
                        <option value="{{ $val }}" {{ $pesanan->status===$val?'selected':'' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
                <input type="text" name="catatan_admin" class="form-inp" placeholder="Catatan (opsional)" style="flex:2;padding:8px 12px;font-size:13px">
                <button type="submit" class="btn btn-primary btn-sm">Perbarui Status</button>
            </form>
        </div>

        {{-- Daftar produk --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:24px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
            <div style="font-family:var(--fd);font-size:16px;font-weight:500;color:var(--soil);margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid var(--sand)">
                Produk Dipesan
            </div>
            @foreach($pesanan->items as $item)
            <div style="display:flex;gap:16px;align-items:center;padding:14px 0;border-bottom:1px solid rgba(176,139,110,.07)">
                <div style="width:54px;height:54px;background:var(--oat);border-radius:var(--r-md);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;overflow:hidden">
                    @php
                        // Parsing gambar aman — bisa string, JSON string, array, collection, atau null
                        $gambarRaw = $item->produk->gambar ?? null;
                        if ($gambarRaw instanceof \Illuminate\Database\Eloquent\Collection || $gambarRaw instanceof \Illuminate\Support\Collection) {
                            $gambarList = $gambarRaw->pluck('path')->filter()->values()->all();
                        } elseif (is_array($gambarRaw)) {
                            $gambarList = array_filter(array_map(fn($g) => is_object($g) ? ($g->path ?? null) : $g, $gambarRaw));
                        } elseif (is_string($gambarRaw) && !empty($gambarRaw)) {
                            $decoded = json_decode($gambarRaw, true);
                            $gambarList = (json_last_error() === JSON_ERROR_NONE && is_array($decoded))
                                ? array_filter($decoded)
                                : [$gambarRaw];
                        } else {
                            $gambarList = [];
                        }
                        $gambarUtama = array_values($gambarList)[0] ?? null;
                    @endphp
                    @if($item->produk && $gambarUtama)
                        <img src="{{ asset('storage/' . $gambarUtama) }}" style="width:100%;height:100%;object-fit:cover" alt="{{ $item->nama_produk }}">
                    @else
                        📦
                    @endif
                </div>
                <div style="flex:1">
                    <div style="font-weight:700;font-size:13.5px;color:var(--soil)">{{ $item->nama_produk }}</div>
                    <div style="font-size:12px;color:var(--clay);margin-top:3px">
                        {{ $item->qty }} {{ $item->satuan }}
                        @if($item->harga_promo)
                            × <span style="text-decoration:line-through">Rp {{ number_format($item->harga_satuan,0,',','.') }}</span>
                            → <strong>Rp {{ number_format($item->harga_promo,0,',','.') }}</strong>
                        @else
                            × Rp {{ number_format($item->harga_satuan,0,',','.') }}
                        @endif
                    </div>
                </div>
                <div style="font-size:15px;font-weight:700;color:var(--soil)">
                    Rp {{ number_format($item->subtotal,0,',','.') }}
                </div>
            </div>
            @endforeach

            {{-- Ringkasan harga --}}
            <div style="margin-top:16px;display:flex;flex-direction:column;gap:8px">
                <div style="display:flex;justify-content:space-between;font-size:13.5px">
                    <span style="color:var(--clay)">Subtotal</span>
                    <span>Rp {{ number_format($pesanan->subtotal,0,',','.') }}</span>
                </div>
                @if($pesanan->diskon_produk > 0)
                <div style="display:flex;justify-content:space-between;font-size:13.5px">
                    <span style="color:var(--clay)">Diskon Produk</span>
                    <span style="color:var(--moss)">− Rp {{ number_format($pesanan->diskon_produk,0,',','.') }}</span>
                </div>
                @endif
                @if($pesanan->diskon_voucher > 0)
                <div style="display:flex;justify-content:space-between;font-size:13.5px">
                    <span style="color:var(--clay)">Voucher{{ $pesanan->voucher ? ' ('.$pesanan->voucher->kode.')' : '' }}</span>
                    <span style="color:var(--moss)">− Rp {{ number_format($pesanan->diskon_voucher,0,',','.') }}</span>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between;font-size:13.5px">
                    <span style="color:var(--clay)">Ongkos Kirim</span>
                    <span>Rp {{ number_format($pesanan->ongkir,0,',','.') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding-top:10px;border-top:2px solid var(--sand)">
                    <span style="font-size:15px;font-weight:700;color:var(--soil)">Total</span>
                    <span style="font-family:var(--fd);font-size:20px;font-weight:700;color:var(--terracotta)">
                        Rp {{ number_format($pesanan->total,0,',','.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Riwayat Pembayaran --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:24px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
            <div style="font-family:var(--fd);font-size:16px;font-weight:500;color:var(--soil);margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid var(--sand)">
                Riwayat Pembayaran
            </div>
            @forelse($pesanan->pembayaran as $bayar)
            <div style="display:flex;gap:16px;align-items:flex-start;padding:14px;background:var(--oat);border-radius:var(--r-md);margin-bottom:10px;border:1px solid rgba(176,139,110,.12)">
                <div style="flex:1">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;flex-wrap:wrap">
                        <span style="font-size:13.5px;font-weight:700;color:var(--soil)">
                            {{ match($bayar->metode) {
                                'transfer_bank'=>'🏦 Transfer Bank',
                                'qris'=>'📱 QRIS',
                                'cod'=>'💵 COD',
                                'dp'=>'💰 DP',
                                default=>ucfirst($bayar->metode)
                            } }}
                        </span>
                        <span class="status-pill {{ $bayar->status==='dikonfirmasi'?'s-lunas':($bayar->status==='ditolak'?'s-batal':'s-pending') }}">
                            {{ ucfirst($bayar->status) }}
                        </span>
                    </div>
                    <div style="font-size:13px;color:var(--clay)">
                        Jumlah: <strong style="color:var(--soil)">Rp {{ number_format($bayar->jumlah,0,',','.') }}</strong>
                        @if($bayar->nama_pengirim) · A/N: {{ $bayar->nama_pengirim }} @endif
                        @if($bayar->bank) · Bank: {{ $bayar->bank }} @endif
                    </div>
                    <div style="font-size:12px;color:var(--clay);margin-top:4px">
                        {{ $bayar->created_at->isoFormat('D MMM Y, HH:mm') }}
                        @if($bayar->dikonfirmasi_at)
                            · Dikonfirmasi {{ $bayar->dikonfirmasi_at->isoFormat('D MMM Y') }}
                            oleh {{ $bayar->dikonfirmasiOleh->name ?? '—' }}
                        @endif
                    </div>
                </div>
                @if($bayar->bukti_path)
                <a href="{{ asset('storage/'.$bayar->bukti_path) }}" target="_blank"
                   style="flex-shrink:0;width:72px;height:72px;display:block;overflow:hidden;border-radius:var(--r-sm);border:1.5px solid rgba(176,139,110,.2)">
                    <img src="{{ asset('storage/'.$bayar->bukti_path) }}" style="width:100%;height:100%;object-fit:cover" alt="Bukti bayar">
                </a>
                @endif
            </div>

            {{-- Aksi konfirmasi/tolak --}}
            @if($bayar->status === 'menunggu')
            <div style="display:flex;gap:8px;margin-top:8px">
                <form action="{{ route('admin.pembayaran.konfirmasi', $bayar->id) }}" method="POST" style="flex:1">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-primary btn-sm" style="width:100%;justify-content:center">✓ Konfirmasi</button>
                </form>
                <form action="{{ route('admin.pembayaran.tolak', $bayar->id) }}" method="POST" style="flex:1"
                      onsubmit="return confirm('Tolak pembayaran ini?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-secondary btn-sm" style="width:100%;justify-content:center;color:#c03030;border-color:rgba(192,48,48,.3)">✕ Tolak</button>
                </form>
            </div>
            @endif

            @empty
            <div style="text-align:center;padding:20px;color:var(--clay);font-size:13px">Belum ada pembayaran tercatat</div>
            @endforelse
        </div>
    </div>

    {{-- ── KOLOM KANAN ── --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- Info Pelanggan --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:22px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
            <div style="font-family:var(--fd);font-size:15.5px;font-weight:500;color:var(--soil);margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--sand)">
                Pelanggan
            </div>
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px">
                <div style="width:42px;height:42px;background:var(--terracotta);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;color:#fff;flex-shrink:0">
                    {{ strtoupper(substr($pesanan->user->name,0,2)) }}
                </div>
                <div>
                    <div style="font-size:14px;font-weight:700;color:var(--soil)">{{ $pesanan->user->name }}</div>
                    <div style="font-size:12.5px;color:var(--clay)">{{ $pesanan->user->email }}</div>
                </div>
            </div>
            <div style="font-size:13px;color:var(--clay)">📞 {{ $pesanan->user->telepon ?? '—' }}</div>
            <a href="{{ route('admin.users.show', $pesanan->user->id) }}"
               style="display:inline-block;margin-top:10px;font-size:12px;color:var(--terracotta);font-weight:700;text-decoration:none">
                Lihat profil lengkap →
            </a>
        </div>

        {{-- Alamat Pengiriman --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:22px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
            <div style="font-family:var(--fd);font-size:15.5px;font-weight:500;color:var(--soil);margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--sand)">
                Alamat Pengiriman
            </div>
            <div style="font-size:13.5px;font-weight:700;color:var(--soil);margin-bottom:4px">{{ $pesanan->penerima }}</div>
            <div style="font-size:13px;color:var(--clay);margin-bottom:4px">📞 {{ $pesanan->telepon_penerima }}</div>
            <div style="font-size:13px;color:var(--soil-light);line-height:1.6">
                {{ $pesanan->alamat_pengiriman }},<br>
                {{ $pesanan->kota_tujuan }}, {{ $pesanan->provinsi_tujuan }}
                @if($pesanan->kode_pos) · {{ $pesanan->kode_pos }} @endif
            </div>
        </div>

        {{-- Info Pengiriman --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:22px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
            <div style="font-family:var(--fd);font-size:15.5px;font-weight:500;color:var(--soil);margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--sand)">
                Informasi Pengiriman
            </div>
            <div style="display:flex;flex-direction:column;gap:9px">
                <div style="display:flex;justify-content:space-between;font-size:13px">
                    <span style="color:var(--clay)">Metode</span>
                    <span style="font-weight:700;color:var(--soil)">
                        {{ $pesanan->jenis_pengiriman==='armada' ? '🚛 Armada Sendiri' : '📦 Ekspedisi' }}
                    </span>
                </div>
                @if($pesanan->jenis_pengiriman === 'armada')
                <div style="display:flex;justify-content:space-between;font-size:13px">
                    <span style="color:var(--clay)">Kurir</span>
                    <span style="font-weight:700;color:var(--soil)">{{ $pesanan->ekspedisi ?? 'Armada Toko Sinar Alam' }}</span>
                </div>
                @elseif($pesanan->ekspedisi)
                <div style="display:flex;justify-content:space-between;font-size:13px">
                    <span style="color:var(--clay)">Kurir</span>
                    <span style="font-weight:700;color:var(--soil)">
                        {{ $pesanan->ekspedisi === 'jnt' ? 'J&T Express' : ($pesanan->ekspedisi === 'jne' ? 'JNE Regular' : ($pesanan->ekspedisi === 'sicepat' ? 'SiCepat HALU' : strtoupper($pesanan->ekspedisi))) }}
                    </span>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between;font-size:13px">
                    <span style="color:var(--clay)">Ongkir</span>
                    <span style="font-weight:700;color:var(--soil)">Rp {{ number_format($pesanan->ongkir,0,',','.') }}</span>
                </div>
                @if($pesanan->dikirim_at)
                <div style="display:flex;justify-content:space-between;font-size:13px">
                    <span style="color:var(--clay)">Tgl Kirim</span>
                    <span style="font-weight:700;color:var(--soil)">{{ $pesanan->dikirim_at->isoFormat('D MMM Y') }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Catatan --}}
        @if($pesanan->catatan)
        <div style="background:rgba(192,142,58,.07);border-radius:var(--r-lg);padding:18px;border-left:3px solid var(--ochre)">
            <div style="font-size:12px;font-weight:700;color:var(--ochre);margin-bottom:6px">📝 CATATAN PELANGGAN</div>
            <div style="font-size:13px;color:var(--soil-light);line-height:1.6">{{ $pesanan->catatan }}</div>
        </div>
        @endif

        {{-- Tombol aksi --}}
        <div style="display:flex;flex-direction:column;gap:8px">
            <a href="{{ route('pesanan.invoice', $pesanan->nomor_pesanan) }}" target="_blank"
               class="btn btn-secondary" style="width:100%;justify-content:center">📄 Download Invoice PDF</a>
            <a href="{{ route('admin.pesanan.index') }}"
               class="btn btn-secondary" style="width:100%;justify-content:center">← Kembali ke Daftar Pesanan</a>
        </div>
    </div>
</div>
@endsection