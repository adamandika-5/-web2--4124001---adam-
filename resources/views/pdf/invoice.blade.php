<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; color: #2C1A0E; line-height: 1.5; }
    .page { padding: 32px; max-width: 800px; margin: 0 auto; }

    /* Header */
    .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; padding-bottom: 20px; border-bottom: 2px solid #C66B3D; }
    .logo-name { font-size: 24px; font-weight: 700; color: #2C1A0E; }
    .logo-tagline { font-size: 11px; color: #B08B6E; margin-top: 3px; }
    .logo-contact { font-size: 11px; color: #5A3A24; margin-top: 6px; line-height: 1.6; }
    .invoice-badge { text-align: right; }
    .invoice-title { font-size: 20px; font-weight: 700; color: #C66B3D; }
    .invoice-nomor { font-size: 13px; font-weight: 700; color: #2C1A0E; margin-top: 4px; }
    .invoice-tgl { font-size: 11px; color: #B08B6E; margin-top: 3px; }

    /* Info boxes */
    .info-row { display: flex; gap: 16px; margin-bottom: 22px; }
    .info-box { flex: 1; background: #FAF7F0; border: 1px solid #E8DCC7; border-radius: 8px; padding: 14px; }
    .info-box-title { font-size: 10px; font-weight: 700; color: #B08B6E; text-transform: uppercase; letter-spacing: .07em; margin-bottom: 7px; }
    .info-box-name { font-size: 13px; font-weight: 700; color: #2C1A0E; }
    .info-box-detail { font-size: 11px; color: #5A3A24; margin-top: 3px; line-height: 1.6; }

    /* Status */
    .status-row { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
    .status-pill { font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 20px; }
    .pill-selesai  { background: #EAF3DE; color: #3B6D11; }
    .pill-proses   { background: #FAEEDA; color: #633806; }
    .pill-pending  { background: #EFEFEF; color: #555; }
    .pill-batal    { background: #FCEBEB; color: #791F1F; }

    /* Tabel produk */
    table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
    thead th { background: #2C1A0E; color: #E8DCC7; font-size: 10.5px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; padding: 9px 12px; text-align: left; }
    thead th:last-child { text-align: right; }
    tbody td { padding: 10px 12px; font-size: 12px; border-bottom: 1px solid #E8DCC7; vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:nth-child(even) td { background: #FAF7F0; }
    .text-right { text-align: right; }
    .fw { font-weight: 700; }

    /* Total --*/
    .total-section { display: flex; justify-content: flex-end; margin-bottom: 24px; }
    .total-table { width: 280px; }
    .total-row { display: flex; justify-content: space-between; padding: 5px 0; font-size: 12px; }
    .total-row.grand { border-top: 2px solid #C66B3D; margin-top: 6px; padding-top: 10px; }
    .total-label { color: #B08B6E; }
    .total-val { font-weight: 700; color: #2C1A0E; }
    .grand .total-label { font-size: 14px; font-weight: 700; color: #2C1A0E; }
    .grand .total-val { font-size: 16px; font-weight: 700; color: #C66B3D; }

    /* Pembayaran info */
    .bayar-box { background: #F4EDE0; border-radius: 8px; padding: 16px; margin-bottom: 24px; }
    .bayar-title { font-size: 11px; font-weight: 700; color: #B08B6E; text-transform: uppercase; letter-spacing: .07em; margin-bottom: 10px; }
    .bayar-grid { display: flex; gap: 24px; }
    .bayar-item-label { font-size: 10.5px; color: #B08B6E; margin-bottom: 2px; }
    .bayar-item-val { font-size: 12px; font-weight: 700; color: #2C1A0E; }

    /* Footer */
    .footer { border-top: 1px solid #E8DCC7; padding-top: 16px; display: flex; justify-content: space-between; font-size: 10.5px; color: #B08B6E; }
    .footer-note { max-width: 380px; line-height: 1.6; }
    .footer-ttd { text-align: center; }
    .ttd-box { width: 120px; height: 60px; border-bottom: 1px solid #2C1A0E; margin-bottom: 6px; }
    .ttd-name { font-size: 11px; font-weight: 700; color: #2C1A0E; }
    .ttd-role { font-size: 10px; color: #B08B6E; }

    /* Watermark status batal */
    .watermark { position: fixed; top: 35%; left: 15%; opacity: .08; font-size: 80px; font-weight: 900; color: #c03030; transform: rotate(-30deg); pointer-events: none; text-transform: uppercase; }
</style>
</head>
<body>
<div class="page">

    @if($pesanan->status === 'batal')
        <div class="watermark">DIBATALKAN</div>
    @endif

    {{-- Header --}}
    <div class="header">
        <div>
            <div class="logo-name">🏗️ Sinar Alam</div>
            <div class="logo-tagline">Toko Material Bangunan Terpercaya</div>
            <div class="logo-contact">
                📍 Jl. Raya Bangil No. 45, Pasuruan, Jawa Timur<br>
                📞 (0343) 555-1234 &nbsp;·&nbsp; ✉️ info@sinaralam.id
            </div>
        </div>
        <div class="invoice-badge">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-nomor">{{ $pesanan->nomor_pesanan }}</div>
            <div class="invoice-tgl">{{ $pesanan->created_at->isoFormat('D MMMM Y') }}</div>
        </div>
    </div>

    {{-- Status --}}
    <div class="status-row">
        @php $pc = match($pesanan->status) {
            'selesai'=>'pill-selesai','diproses'=>'pill-proses',
            'batal'=>'pill-batal',default=>'pill-pending'
        }; @endphp
        <span class="status-pill {{ $pc }}">Status: {{ strtoupper($pesanan->status) }}</span>
        @php $bpc = match($pesanan->status_pembayaran) {
            'lunas'=>'pill-selesai','dp'=>'pill-proses',default=>'pill-pending'
        }; @endphp
        <span class="status-pill {{ $bpc }}">Pembayaran: {{ strtoupper($pesanan->status_pembayaran) }}</span>
    </div>

    {{-- Info Pemesan & Pengiriman --}}
    <div class="info-row">
        <div class="info-box">
            <div class="info-box-title">Pemesan</div>
            <div class="info-box-name">{{ $pesanan->user->name }}</div>
            <div class="info-box-detail">
                {{ $pesanan->user->email }}<br>
                {{ $pesanan->user->telepon ?? '-' }}
            </div>
        </div>
        <div class="info-box">
            <div class="info-box-title">Dikirim ke</div>
            <div class="info-box-name">{{ $pesanan->penerima }}</div>
            <div class="info-box-detail">
                📞 {{ $pesanan->telepon_penerima }}<br>
                {{ $pesanan->alamat_pengiriman }}<br>
                {{ $pesanan->kota_tujuan }}, {{ $pesanan->provinsi_tujuan }}
                @if($pesanan->kode_pos) · {{ $pesanan->kode_pos }} @endif
            </div>
        </div>
        <div class="info-box">
            <div class="info-box-title">Pengiriman</div>
            <div class="info-box-name">
                {{ $pesanan->jenis_pengiriman === 'armada' ? 'Armada Sendiri' : 'Ekspedisi' }}
            </div>
            <div class="info-box-detail">
                @if($pesanan->ekspedisi) {{ strtoupper($pesanan->ekspedisi) }}<br>@endif
                Ongkir: Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}
                @if($pesanan->dikirim_at)<br>Dikirim: {{ $pesanan->dikirim_at->format('d M Y') }}@endif
            </div>
        </div>
    </div>

    {{-- Tabel Produk --}}
    <table>
        <thead>
            <tr>
                <th style="width:5%">No</th>
                <th style="width:42%">Nama Produk</th>
                <th style="width:12%">Satuan</th>
                <th style="width:8%">Qty</th>
                <th style="width:15%">Harga Satuan</th>
                <th style="width:18%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pesanan->items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    <span class="fw">{{ $item->nama_produk }}</span>
                    @if($item->harga_promo)
                        <br><span style="font-size:10.5px;color:#B08B6E">Harga promo dari Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                    @endif
                </td>
                <td>{{ $item->satuan }}</td>
                <td class="text-right">{{ $item->qty }}</td>
                <td class="text-right">Rp {{ number_format($item->harga_promo ?? $item->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right fw">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Total --}}
    <div class="total-section">
        <div class="total-table">
            <div class="total-row">
                <span class="total-label">Subtotal</span>
                <span class="total-val">Rp {{ number_format($pesanan->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($pesanan->diskon_produk > 0)
            <div class="total-row">
                <span class="total-label">Diskon Produk</span>
                <span class="total-val" style="color:#606C38">− Rp {{ number_format($pesanan->diskon_produk, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($pesanan->diskon_voucher > 0)
            <div class="total-row">
                <span class="total-label">Voucher {{ $pesanan->voucher?->kode }}</span>
                <span class="total-val" style="color:#606C38">− Rp {{ number_format($pesanan->diskon_voucher, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="total-row">
                <span class="total-label">Ongkos Kirim</span>
                <span class="total-val">Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</span>
            </div>
            <div class="total-row grand">
                <span class="total-label">TOTAL</span>
                <span class="total-val">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- Info Pembayaran --}}
    <div class="bayar-box">
        <div class="bayar-title">Informasi Pembayaran</div>
        <div class="bayar-grid">
            @foreach([
                ['BCA', '1234567890', 'Sinar Alam Jaya'],
                ['Mandiri', '9876543210', 'Sinar Alam Jaya'],
                ['BRI', '0001234567891', 'Sinar Alam Jaya'],
            ] as [$bank, $norek, $nama])
            <div>
                <div class="bayar-item-label">Bank {{ $bank }}</div>
                <div class="bayar-item-val">{{ $norek }}</div>
                <div style="font-size:10.5px;color:#B08B6E">A/N {{ $nama }}</div>
            </div>
            @endforeach
            <div>
                <div class="bayar-item-label">QRIS</div>
                <div class="bayar-item-val">Scan di kasir</div>
                <div style="font-size:10.5px;color:#B08B6E">Semua e-wallet</div>
            </div>
        </div>
        @if($pesanan->catatan)
        <div style="margin-top:12px;padding-top:10px;border-top:1px solid #E8DCC7;font-size:11.5px;color:#5A3A24">
            📝 Catatan: {{ $pesanan->catatan }}
        </div>
        @endif
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-note">
            Invoice ini diterbitkan secara otomatis oleh sistem Sinar Alam.<br>
            Harap melakukan pembayaran sesuai nominal dalam 1×24 jam.<br>
            Pertanyaan? Hubungi kami di (0343) 555-1234 atau WhatsApp.
        </div>
        <div class="footer-ttd">
            <div class="ttd-box"></div>
            <div class="ttd-name">Sinar Alam</div>
            <div class="ttd-role">Tim Penjualan</div>
        </div>
    </div>

</div>
</body>
</html>