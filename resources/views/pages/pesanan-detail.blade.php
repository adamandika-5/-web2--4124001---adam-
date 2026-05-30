@extends('layouts.app')
@section('title', 'Detail Pesanan ' . $pesanan->nomor_pesanan)

@section('content')
<div style="max-width:900px;margin:0 auto;padding:36px 48px">

    {{-- Breadcrumb --}}
    <nav style="font-size:13px;color:var(--clay);display:flex;align-items:center;gap:7px;margin-bottom:24px;flex-wrap:wrap">
        <a href="{{ route('beranda') }}" style="color:var(--terracotta);text-decoration:none;font-weight:600">Beranda</a>
        <span>›</span>
        <a href="{{ route('pesanan.index') }}" style="color:var(--terracotta);text-decoration:none;font-weight:600">Pesanan Saya</a>
        <span>›</span>
        <span>{{ $pesanan->nomor_pesanan }}</span>
    </nav>

    {{-- Header --}}
    <div style="background:var(--soil);border-radius:var(--r-xl);padding:28px 32px;margin-bottom:20px;position:relative;overflow:hidden">
        <div style="position:absolute;inset:0;background:radial-gradient(ellipse 60% 70% at 85% 30%,rgba(198,107,61,.25) 0%,transparent 55%)"></div>
        <div class="grain"></div>
        <div style="position:relative;z-index:2;display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div>
                <div style="font-size:11px;color:rgba(232,220,199,.5);font-weight:700;letter-spacing:.07em;text-transform:uppercase;margin-bottom:5px">Nomor Pesanan</div>
                <div style="font-family:var(--fs);font-size:22px;font-weight:700;color:var(--sand)">{{ $pesanan->nomor_pesanan }}</div>
                <div style="font-size:13px;color:rgba(232,220,199,.5);margin-top:5px">{{ $pesanan->created_at->isoFormat('dddd, D MMMM Y · HH:mm') }} WIB</div>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px">
                @php $sc = match($pesanan->status) {
                    'selesai'=>'s-lunas','diproses'=>'s-proses','dikirim'=>'s-proses',
                    'pending'=>'s-pending','batal'=>'s-batal',default=>'s-pending'
                }; @endphp
                <span class="status-pill {{ $sc }}" style="font-size:13px;padding:6px 16px">
                    {{ match($pesanan->status) {
                        'selesai'=>'✓ Selesai','diproses'=>'⚙ Diproses','dikirim'=>'🚚 Dikirim',
                        'dikonfirmasi'=>'✓ Dikonfirmasi','pending'=>'⏳ Menunggu','batal'=>'✕ Dibatalkan',
                        default=>ucfirst($pesanan->status)
                    } }}
                </span>
                @if($pesanan->status !== 'batal' && $pesanan->status !== 'selesai')
                    <a href="{{ route('lacak.show', $pesanan->nomor_pesanan) }}"
                       style="font-size:12px;color:rgba(232,220,199,.5);text-decoration:none;font-weight:600"
                       onmouseover="this.style.color='var(--terracotta)'"
                       onmouseout="this.style.color='rgba(232,220,199,.5)'">
                        🔍 Lacak Pesanan →
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">

        {{-- Kiri --}}
        <div style="display:flex;flex-direction:column;gap:16px">

            {{-- Item --}}
            <div style="background:#fff;border-radius:var(--r-lg);padding:22px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08)">
                <div style="font-family:var(--fs);font-size:16px;font-weight:700;color:var(--soil);margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--sand)">
                    Produk Dipesan
                </div>
                @foreach($pesanan->items as $item)
                <div style="display:flex;gap:14px;align-items:center;padding:12px 0;border-bottom:1px solid rgba(176,139,110,.06)">
                    <div style="width:52px;height:52px;background:var(--oat);border-radius:var(--r-md);display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;overflow:hidden">
                    @php
                        // Parsing gambar produk secara aman — bisa berupa string, JSON, array, collection, atau null
                        $gambarRaw = $item->produk->gambar ?? null;
                        if ($gambarRaw instanceof \Illuminate\Database\Eloquent\Collection || $gambarRaw instanceof \Illuminate\Support\Collection) {
                            // Relasi hasMany → ambil path dari tiap object
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
                        <img src="{{ asset('storage/' . $gambarUtama) }}"
                             style="width:100%;height:100%;object-fit:cover" alt="{{ $item->nama_produk }}">
                    @else
                        📦
                    @endif
                    </div>
                    <div style="flex:1">
                        @if($item->produk)
                            <a href="{{ route('produk.show', $item->produk->slug) }}"
                               style="font-size:14px;font-weight:700;color:var(--soil);text-decoration:none"
                               onmouseover="this.style.color='var(--terracotta)'"
                               onmouseout="this.style.color='var(--soil)'">
                               {{ $item->nama_produk }}
                            </a>
                        @else
                            <div style="font-size:14px;font-weight:700;color:var(--soil)">{{ $item->nama_produk }}</div>
                        @endif
                        <div style="font-size:12px;color:var(--clay);margin-top:3px">
                            {{ $item->qty }} {{ $item->satuan }}
                            @if($item->harga_promo)
                                × <span style="text-decoration:line-through;color:var(--concrete)">Rp {{ number_format($item->harga_satuan,0,',','.') }}</span>
                                → <strong>Rp {{ number_format($item->harga_promo,0,',','.') }}</strong>
                            @else
                                × Rp {{ number_format($item->harga_satuan,0,',','.') }}
                            @endif
                        </div>
                    </div>
                    <div style="font-size:15px;font-weight:700;color:var(--soil);text-align:right">
                        Rp {{ number_format($item->subtotal,0,',','.') }}
                    </div>
                </div>
                @endforeach

                {{-- Ringkasan harga --}}
                <div style="margin-top:14px;display:flex;flex-direction:column;gap:8px">
                    <div style="display:flex;justify-content:space-between;font-size:13.5px">
                        <span style="color:var(--clay)">Subtotal</span>
                        <span>Rp {{ number_format($pesanan->subtotal,0,',','.') }}</span>
                    </div>
                    @if($pesanan->diskon_voucher > 0)
                    <div style="display:flex;justify-content:space-between;font-size:13.5px">
                        <span style="color:var(--clay)">Diskon Voucher{{ $pesanan->voucher ? ' ('.$pesanan->voucher->kode.')' : '' }}</span>
                        <span style="color:var(--moss)">− Rp {{ number_format($pesanan->diskon_voucher,0,',','.') }}</span>
                    </div>
                    @endif
                    <div style="display:flex;justify-content:space-between;font-size:13.5px">
                        <span style="color:var(--clay)">Ongkos Kirim</span>
                        <span>Rp {{ number_format($pesanan->ongkir,0,',','.') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding-top:10px;border-top:2px solid var(--sand)">
                        <span style="font-size:15px;font-weight:700;color:var(--soil)">Total Pembayaran</span>
                        <span style="font-family:var(--fs);font-size:20px;font-weight:700;color:var(--terracotta)">
                            Rp {{ number_format($pesanan->total,0,',','.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Upload Bukti Bayar --}}
            @if($pesanan->status_pembayaran === 'menunggu' && $pesanan->status !== 'batal')
            <div style="background:#fff;border-radius:var(--r-lg);padding:22px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08)">
                <div style="font-family:var(--fs);font-size:16px;font-weight:700;color:var(--soil);margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--sand)">
                    💳 Upload Bukti Pembayaran
                </div>
                <form action="{{ route('pesanan.bayar', $pesanan->nomor_pesanan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        <div class="form-grp">
                            <label class="form-lbl">Nama Bank</label>
                            <select class="form-inp" name="bank">
                                <option value="">Pilih bank</option>
                                @foreach(['BCA','BNI','Mandiri','BRI','BSI','QRIS'] as $bank)
                                    <option value="{{ $bank }}">{{ $bank }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-grp">
                            <label class="form-lbl">Nama Pengirim</label>
                            <input class="form-inp" type="text" name="nama_pengirim"
                                   value="{{ auth()->user()->name }}" placeholder="Nama rekening pengirim">
                        </div>
                    </div>
                    <div class="form-grp">
                        <label class="form-lbl">Foto Bukti Transfer *</label>
                        <input type="file" name="bukti" accept="image/*" class="form-inp"
                               style="padding:7px;font-size:13px" required>
                        <div style="font-size:11.5px;color:var(--clay);margin-top:4px">JPG/PNG, maks 3MB</div>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px">
                        📤 Kirim Bukti Pembayaran
                    </button>
                </form>
            </div>
            @endif

            {{-- Riwayat Pembayaran --}}
            @if($pesanan->pembayaran->isNotEmpty())
            <div style="background:#fff;border-radius:var(--r-lg);padding:22px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08)">
                <div style="font-family:var(--fs);font-size:16px;font-weight:700;color:var(--soil);margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid var(--sand)">
                    Riwayat Pembayaran
                </div>
                @foreach($pesanan->pembayaran as $bayar)
                <div style="display:flex;gap:14px;align-items:flex-start;padding:12px;background:var(--oat);border-radius:var(--r-md);margin-bottom:8px;border:1px solid rgba(176,139,110,.12)">
                    @if($bayar->bukti_path)
                        <a href="{{ asset('storage/'.$bayar->bukti_path) }}" target="_blank"
                           style="width:52px;height:52px;display:block;overflow:hidden;border-radius:var(--r-sm);flex-shrink:0">
                            <img src="{{ asset('storage/'.$bayar->bukti_path) }}" style="width:100%;height:100%;object-fit:cover">
                        </a>
                    @endif
                    <div style="flex:1">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:5px;flex-wrap:wrap">
                            <span style="font-size:13.5px;font-weight:700;color:var(--soil)">
                                Rp {{ number_format($bayar->jumlah,0,',','.') }}
                            </span>
                            <span class="status-pill {{ $bayar->status==='dikonfirmasi'?'s-lunas':($bayar->status==='ditolak'?'s-batal':'s-pending') }}">
                                {{ ucfirst($bayar->status) }}
                            </span>
                        </div>
                        <div style="font-size:12px;color:var(--clay)">
                            {{ $bayar->bank ? 'Bank '.$bayar->bank : ucfirst($bayar->metode) }}
                            @if($bayar->nama_pengirim) · A/N {{ $bayar->nama_pengirim }} @endif
                        </div>
                        <div style="font-size:11.5px;color:var(--clay);margin-top:3px">{{ $bayar->created_at->diffForHumans() }}</div>
                        @if($bayar->catatan_admin)
                            <div style="font-size:12px;color:#c03030;margin-top:4px">Catatan admin: {{ $bayar->catatan_admin }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Kanan --}}
        <div style="display:flex;flex-direction:column;gap:14px">

            {{-- Alamat --}}
            <div style="background:#fff;border-radius:var(--r-lg);padding:20px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08)">
                <div style="font-size:13px;font-weight:700;color:var(--clay);text-transform:uppercase;letter-spacing:.05em;margin-bottom:12px">📍 Alamat Pengiriman</div>
                <div style="font-size:14px;font-weight:700;color:var(--soil);margin-bottom:4px">{{ $pesanan->penerima }}</div>
                <div style="font-size:13px;color:var(--clay);margin-bottom:4px">📞 {{ $pesanan->telepon_penerima }}</div>
                <div style="font-size:13px;color:var(--soil-light);line-height:1.55">
                    {{ $pesanan->alamat_pengiriman }},<br>
                    {{ $pesanan->kota_tujuan }}, {{ $pesanan->provinsi_tujuan }}
                    @if($pesanan->kode_pos) · {{ $pesanan->kode_pos }} @endif
                </div>
            </div>

            {{-- Pengiriman --}}
            <div style="background:#fff;border-radius:var(--r-lg);padding:20px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08)">
                <div style="font-size:13px;font-weight:700;color:var(--clay);text-transform:uppercase;letter-spacing:.05em;margin-bottom:12px">🚚 Info Pengiriman</div>
                <div style="display:flex;flex-direction:column;gap:8px;font-size:13px">
                    <div style="display:flex;justify-content:space-between">
                        <span style="color:var(--clay)">Metode</span>
                        <span style="font-weight:700;color:var(--soil)">
                            {{ $pesanan->jenis_pengiriman==='armada' ? '🚛 Armada Sendiri' : '📦 Ekspedisi' }}
                        </span>
                    </div>
                    @if($pesanan->jenis_pengiriman === 'armada')
                    <div style="display:flex;justify-content:space-between">
                        <span style="color:var(--clay)">Kurir</span>
                        <span style="font-weight:700;color:var(--soil)">Pihak Toko / Armada Sinar Alam</span>
                    </div>
                    @elseif($pesanan->ekspedisi)
                    <div style="display:flex;justify-content:space-between">
                        <span style="color:var(--clay)">Kurir</span>
                        <span style="font-weight:700;color:var(--soil)">{{ strtoupper($pesanan->ekspedisi) }}</span>
                    </div>
                    @endif
                    <div style="display:flex;justify-content:space-between">
                        <span style="color:var(--clay)">Ongkir</span>
                        <span style="font-weight:700;color:var(--soil)">Rp {{ number_format($pesanan->ongkir,0,',','.') }}</span>
                    </div>
                    @if($pesanan->dikirim_at)
                    <div style="display:flex;justify-content:space-between">
                        <span style="color:var(--clay)">Tgl Kirim</span>
                        <span style="font-weight:700;color:var(--moss)">{{ $pesanan->dikirim_at->isoFormat('D MMM Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Aksi --}}
            <div style="display:flex;flex-direction:column;gap:8px">
                <a href="{{ route('pesanan.invoice', $pesanan->nomor_pesanan) }}" target="_blank"
                   class="btn btn-secondary" style="width:100%;justify-content:center;font-size:13px">
                    📄 Download Invoice PDF
                </a>
                @if($pesanan->status === 'pending')
                <form action="{{ route('pesanan.batal', $pesanan->nomor_pesanan) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                    @csrf @method('POST')
                    <button type="submit" class="btn btn-secondary"
                            style="width:100%;justify-content:center;font-size:13px;color:#c03030;border-color:rgba(192,48,48,.3)">
                        ✕ Batalkan Pesanan
                    </button>
                </form>
                @endif
                <a href="{{ route('pesanan.index') }}"
                   style="display:block;text-align:center;font-size:13px;color:var(--terracotta);font-weight:600;text-decoration:none;margin-top:4px">
                    ← Kembali ke Pesanan Saya
                </a>
            </div>

            {{-- Catatan --}}
            @if($pesanan->catatan)
            <div style="background:rgba(192,142,58,.07);border-radius:var(--r-md);padding:14px;border-left:3px solid var(--ochre)">
                <div style="font-size:11.5px;font-weight:700;color:var(--ochre);margin-bottom:5px">📝 CATATAN ANDA</div>
                <div style="font-size:13px;color:var(--soil-light)">{{ $pesanan->catatan }}</div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection