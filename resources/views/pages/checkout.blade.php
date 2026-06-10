@extends('layouts.app')
@section('title', 'Checkout')

@push('styles')
<style>
    .ekspedisi-card {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        padding: 14px !important;
        border: 2px solid var(--sand) !important;
        border-radius: var(--r-md) !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
        background: #fff !important;
        text-align: left !important;
    }
    .ekspedisi-card:hover {
        border-color: var(--terracotta) !important;
    }
    .ekspedisi-card:has(input:checked) {
        border-color: var(--terracotta) !important;
        background: rgba(198, 107, 61, 0.04) !important;
    }
</style>
@endpush

@section('content')

{{-- Stepper --}}
<div class="checkout-stepper-wrap" style="background:#fff;border-bottom:1px solid rgba(176,139,110,.12)">
    <div class="checkout-stepper" style="padding:14px 28px">
        <span style="color:var(--clay);display:flex;align-items:center;gap:6px">
            <span style="width:22px;height:22px;background:var(--moss);color:#fff;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:11px">✓</span>
            Keranjang
        </span>
        <span style="flex:1;height:1.5px;background:var(--terracotta);max-width:60px"></span>
        <span style="color:var(--terracotta);display:flex;align-items:center;gap:6px">
            <span style="width:22px;height:22px;background:var(--terracotta);color:#fff;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700">2</span>
            Pengiriman & Pembayaran
        </span>
        <span style="flex:1;height:1.5px;background:var(--sand);max-width:60px"></span>
        <span style="color:var(--clay);display:flex;align-items:center;gap:6px">
            <span style="width:22px;height:22px;background:var(--sand);color:var(--clay);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700">3</span>
            Selesai
        </span>
    </div>
</div>

<form action="{{ route('checkout.proses') }}" method="POST" id="checkoutForm">
@csrf

<div class="page-section resp-grid-checkout" style="padding-top:32px;padding-bottom:48px">

    {{-- ── KIRI ── --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- Alamat Pengiriman --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:24px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08)">
            <div style="font-family:var(--fs);font-size:17px;font-weight:700;color:var(--soil);margin-bottom:18px;display:flex;align-items:center;justify-content:space-between">
                📍 Alamat Pengiriman
                <a href="{{ route('profil.alamat') }}" style="font-size:12px;color:var(--terracotta);font-weight:600;text-decoration:none;font-family:var(--fb)">+ Tambah Alamat</a>
            </div>

            @if($alamats->isEmpty())
                <div style="text-align:center;padding:24px;background:var(--oat);border-radius:var(--r-md);border:1.5px dashed var(--sand)">
                    <div style="font-size:13.5px;color:var(--clay);margin-bottom:12px">Belum ada alamat tersimpan</div>
                    <a href="{{ route('profil.alamat') }}" class="btn btn-primary btn-sm">Tambah Alamat Sekarang</a>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:10px">
                    @foreach($alamats as $alamat)
                    <label style="display:flex;gap:14px;padding:16px;border:2px solid {{ $alamat->is_utama ? 'var(--terracotta)' : 'var(--sand)' }};border-radius:var(--r-lg);cursor:pointer;transition:border-color .2s;background:{{ $alamat->is_utama ? 'rgba(198,107,61,.04)' : '#fff' }}"
                           onmouseover="this.style.borderColor='var(--terracotta)'" onmouseout="this.style.borderColor='{{ $alamat->is_utama ? 'var(--terracotta)' : 'var(--sand)' }}'">
                        <input type="radio" name="alamat_id" value="{{ $alamat->id }}" data-kota="{{ $alamat->kota }}" data-alamat="{{ $alamat->alamat_lengkap }}"
                               {{ $alamat->is_utama || $loop->first ? 'checked' : '' }}
                               style="accent-color:var(--terracotta);margin-top:3px;flex-shrink:0">
                        <div style="flex:1">
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:5px">
                                <span style="font-size:13.5px;font-weight:700;color:var(--soil)">{{ $alamat->penerima }}</span>
                                <span style="font-size:11.5px;font-weight:700;color:var(--clay);background:var(--oat);padding:2px 8px;border-radius:20px">{{ $alamat->label }}</span>
                                @if($alamat->is_utama)
                                    <span style="font-size:10.5px;font-weight:700;color:var(--terracotta);background:rgba(198,107,61,.1);padding:2px 8px;border-radius:20px">Utama</span>
                                @endif
                            </div>
                            <div style="font-size:13px;color:var(--clay)">📞 {{ $alamat->telepon }}</div>
                            <div style="font-size:13px;color:var(--soil-light);margin-top:4px">
                                {{ $alamat->alamat_lengkap }}, {{ $alamat->kecamatan ? $alamat->kecamatan.', ' : '' }}{{ $alamat->kota }}, {{ $alamat->provinsi }}
                                @if($alamat->kode_pos) · {{ $alamat->kode_pos }} @endif
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Metode Pengiriman --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:24px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08)">
            <div style="font-family:var(--fs);font-size:17px;font-weight:700;color:var(--soil);margin-bottom:18px">
                🚚 Metode Pengiriman
            </div>

            @if(!$tampilArmada && !$tampilEkspedisi)
                {{-- Fallback jika tidak ada opsi (tidak seharusnya terjadi) --}}
                <div style="padding:14px;background:var(--oat);border-radius:var(--r-md);font-size:13px;color:var(--clay)">
                    ⚠️ Tidak ada metode pengiriman yang tersedia untuk produk ini.
                </div>
            @else
            <div style="display:flex;flex-direction:column;gap:10px">

                @if($tampilArmada)
                {{-- Armada --}}
                <label id="labelArmada" style="display:flex;gap:14px;padding:16px;border:2px solid {{ $defaultPengiriman==='armada' ? 'var(--terracotta)' : 'var(--sand)' }};border-radius:var(--r-lg);cursor:pointer;transition:border-color .2s;background:{{ $defaultPengiriman==='armada' ? 'rgba(198,107,61,.04)' : '#fff' }}"
                       onmouseover="this.style.borderColor='var(--terracotta)'" onmouseout="if(!this.querySelector('input').checked)this.style.borderColor='var(--sand)'">
                    <input type="radio" name="jenis_pengiriman" value="armada" onchange="updatePengiriman(this)"
                           {{ $defaultPengiriman==='armada' ? 'checked' : '' }}
                           style="accent-color:var(--terracotta);margin-top:3px;flex-shrink:0">
                    <div style="flex:1">
                        <div style="font-size:14px;font-weight:700;color:var(--soil);margin-bottom:3px">
                            🚛 Armada Sendiri Sinar Alam
                        </div>
                        <div style="font-size:12.5px;color:var(--clay)">
                            Khusus Jawa Timur · Material besar (pasir, semen massal, bata, genteng)
                        </div>
                        <div style="font-size:12px;color:var(--moss);margin-top:4px;font-weight:600">Estimasi 1–3 hari kerja</div>
                    </div>
                    <div style="font-size:14px;font-weight:700;color:var(--terracotta);white-space:nowrap;align-self:center" id="ongkirArmada">
                        Rp —
                    </div>
                </label>
                @endif

                @if($tampilEkspedisi)
                {{-- Ekspedisi --}}
                <div id="wrapEkspedisi" style="border:2px solid {{ $defaultPengiriman==='ekspedisi' ? 'var(--terracotta)' : 'var(--sand)' }};border-radius:var(--r-lg);overflow:hidden">
                    <label style="display:flex;gap:14px;padding:16px;cursor:pointer;background:#fff;transition:background .2s"
                           onmouseover="this.style.background='rgba(198,107,61,.03)'" onmouseout="this.style.background='#fff'">
                        <input type="radio" name="jenis_pengiriman" value="ekspedisi" onchange="updatePengiriman(this)"
                               {{ $defaultPengiriman==='ekspedisi' ? 'checked' : '' }}
                               style="accent-color:var(--terracotta);margin-top:3px;flex-shrink:0">
                        <div>
                            <div style="font-size:14px;font-weight:700;color:var(--soil);margin-bottom:3px">📦 Ekspedisi</div>
                            <div style="font-size:12.5px;color:var(--clay)">J&T Express · JNE · SiCepat — barang ringan &amp; sedang</div>
                        </div>
                    </label>

                    {{-- Sub-pilihan ekspedisi --}}
                    <div id="ekspedisiOptions" style="padding:0 16px 16px;display:{{ $defaultPengiriman==='ekspedisi' ? 'grid' : 'none' }};grid-template-columns:1fr 1fr 1fr;gap:8px">
                        @foreach([
                            ['jnt','J&T Express','2-4 hari'],
                            ['jne','JNE Regular','2-5 hari'],
                            ['sicepat','SiCepat HALU','1-3 hari'],
                        ] as [$val, $nama, $est])
                        <label class="ekspedisi-card">
                            <input type="radio" name="ekspedisi" value="{{ $val }}" {{ $val==='jnt'?'checked':'' }}
                                   style="accent-color:var(--terracotta);margin:0;flex-shrink:0">
                            <div>
                                <span style="display:block;font-size:13px;font-weight:700;color:var(--soil)">{{ $nama }}</span>
                                <span style="display:block;font-size:11px;color:var(--clay);margin-top:2px">{{ $est }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
            @endif
        </div>

        {{-- Metode Pembayaran --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:24px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08)">
            <div style="font-family:var(--fs);font-size:17px;font-weight:700;color:var(--soil);margin-bottom:18px">
                💳 Metode Pembayaran
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                @foreach([
                    ['transfer_bank','🏦','Transfer Bank','BCA, BNI, Mandiri, BRI'],
                    ['qris','📱','QRIS','Scan & bayar semua e-wallet'],
                    ['cod','💵','COD (Bayar di Tempat)','Khusus area Jombang'],
                    ['dp','💰','Bayar DP','Min. 30% untuk proyek besar'],
                ] as [$val,$ikon,$nama,$desc])
                <label style="display:flex;gap:12px;padding:14px;border:2px solid var(--sand);border-radius:var(--r-lg);cursor:pointer;transition:border-color .2s"
                       onmouseover="this.style.borderColor='var(--terracotta)'" onmouseout="if(!this.querySelector('input').checked)this.style.borderColor='var(--sand)'">
                    <input type="radio" name="metode_bayar" value="{{ $val }}"
                           {{ $val==='transfer_bank'?'checked':'' }}
                           style="accent-color:var(--terracotta);margin-top:2px;flex-shrink:0">
                    <div>
                        <div style="font-size:14px;font-weight:700;color:var(--soil)">{{ $ikon }} {{ $nama }}</div>
                        <div style="font-size:12px;color:var(--clay);margin-top:2px">{{ $desc }}</div>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Catatan --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:20px 24px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08)">
            <label class="form-lbl" style="display:block;margin-bottom:8px">📝 Catatan untuk Penjual (opsional)</label>
            <textarea name="catatan" rows="3" class="form-inp"
                      placeholder="Contoh: tolong kirim pagi hari, atau titip ke satpam jika tidak ada di rumah..."></textarea>
        </div>

        {{-- Kode Voucher --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:20px 24px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08)">
            <div style="font-family:var(--fs);font-size:16px;font-weight:700;color:var(--soil);margin-bottom:14px">
                🎫 Kode Voucher
            </div>

            {{-- Status voucher aktif --}}
            <div id="voucherAktifBox" style="display:none;background:rgba(96,108,56,.07);border:1.5px solid rgba(96,108,56,.25);border-radius:var(--r-md);padding:12px 14px;margin-bottom:12px">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:10px">
                    <div>
                        <div style="font-size:13px;font-weight:700;color:var(--moss)" id="voucherAktifLabel">✅ Voucher diterapkan</div>
                        <div style="font-size:12px;color:var(--clay);margin-top:2px" id="voucherAktifInfo"></div>
                    </div>
                    <button type="button" onclick="hapusVoucher()"
                            style="flex-shrink:0;background:none;border:1px solid var(--sand);border-radius:var(--r-sm);padding:5px 10px;font-size:12px;color:var(--clay);cursor:pointer;font-family:var(--fb);transition:all .2s"
                            onmouseover="this.style.borderColor='#c03030';this.style.color='#c03030'"
                            onmouseout="this.style.borderColor='var(--sand)';this.style.color='var(--clay)'">
                        🗑 Hapus
                    </button>
                </div>
            </div>

            {{-- Input kode --}}
            <div id="voucherInputBox">
                <div style="display:flex;gap:8px">
                    <input type="text" id="inputKodeVoucher"
                           class="form-inp"
                           placeholder="Masukkan kode voucher"
                           style="text-transform:uppercase;font-weight:700;font-family:monospace;letter-spacing:.06em;flex:1"
                           oninput="this.value=this.value.toUpperCase()">
                    <button type="button" onclick="terapkanVoucher()"
                            class="btn btn-primary btn-sm" style="flex-shrink:0;padding:0 18px">
                        Terapkan
                    </button>
                </div>
                <div id="voucherPesan" style="font-size:12.5px;margin-top:7px;display:none"></div>
            </div>

            {{-- Hidden inputs untuk form submit --}}
            <input type="hidden" name="kode_voucher" id="hiddenKodeVoucher">
        </div>

        {{-- Input tersembunyi --}}
        <input type="hidden" name="ongkir" id="ongkirVal" value="0">
        <input type="hidden" name="diskon_voucher" id="diskonVoucherVal" value="0">
    </div>

    {{-- ── KANAN: RINGKASAN ── --}}
    <div class="checkout-summary" style="position:sticky;top:90px">

        {{-- Ringkasan Item --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:20px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08);margin-bottom:14px">
            <div style="font-size:13.5px;font-weight:700;color:var(--soil);margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--sand)">
                Ringkasan Pesanan ({{ $checkoutQuantity }} produk)
            </div>
            <div style="display:flex;flex-direction:column;gap:8px;max-height:180px;overflow-y:auto;margin-bottom:14px">
                @foreach($items as $item)
                <div style="display:flex;gap:10px;align-items:center">
                    <div style="width:36px;height:36px;background:var(--oat);border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;overflow:hidden">
                        @if($item->attributes->gambar)
                            <img src="{{ asset('storage/'.$item->attributes->gambar) }}" style="width:100%;height:100%;object-fit:cover">
                        @else 📦 @endif
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:12.5px;font-weight:600;color:var(--soil);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $item->name }}</div>
                        <div style="font-size:11.5px;color:var(--clay)">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                    </div>
                    <div style="font-size:13px;font-weight:700;color:var(--soil);white-space:nowrap">
                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Totals --}}
            @php $subtotal = $checkoutSubtotal; @endphp
            <div style="border-top:1px solid var(--sand);padding-top:12px;display:flex;flex-direction:column;gap:8px">
                <div style="display:flex;justify-content:space-between;font-size:13px">
                    <span style="color:var(--clay)">Subtotal</span>
                    <span style="font-weight:600">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px">
                    <span style="color:var(--clay)">Ongkos Kirim</span>
                    <span id="ongkirDisplay" style="font-weight:600;color:var(--ochre)">Pilih pengiriman</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px">
                    <span style="color:var(--clay)">Potongan Voucher</span>
                    <span id="voucherDisplay" style="font-weight:600;color:var(--moss)">- Rp 0</span>
                </div>
                <div style="border-top:1.5px solid var(--sand);padding-top:10px;display:flex;justify-content:space-between;font-size:15px;font-weight:700;color:var(--soil)">
                    <span>Total Bayar</span>
                    <span id="totalDisplay" style="color:var(--terracotta)">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:14px;font-size:15px">
            Buat Pesanan
        </button>

        <a href="{{ route('keranjang.index') }}" style="display:block;text-align:center;margin-top:12px;font-size:13px;color:var(--terracotta);font-weight:600;text-decoration:none">
            ← Kembali ke keranjang
        </a>
    </div>

</div>
</form>

@endsection

@push('scripts')
<script>
const subtotal = Math.round(Number({{ $checkoutSubtotal }})) || 0;
const ONGKIR_ARMADA_DEFAULT = 25000;
let diskonVoucher = 0;

function updateTotal() {
    const ongkir = parseInt(document.getElementById('ongkirVal').value) || 0;
    const total  = Math.max(0, subtotal - diskonVoucher + ongkir);
    document.getElementById('totalDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

function tampilkanOngkir(ongkirNum) {
    const o = Math.round(ongkirNum);
    document.getElementById('ongkirVal').value = o;
    document.getElementById('ongkirDisplay').textContent = 'Rp ' + o.toLocaleString('id-ID');
    document.getElementById('ongkirDisplay').style.color = 'var(--soil)';
    updateTotal();
}

async function hitungOngkir(kota, jenis) {
    if (!kota || jenis !== 'armada') return;
    tampilkanOngkir(ONGKIR_ARMADA_DEFAULT);
    try {
        const res = await fetch('{{ route("checkout.ongkir") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({
                kota,
                alamat_lengkap: document.querySelector('[name="alamat_id"]:checked')?.getAttribute('data-alamat') || '',
                jenis_kendaraan: 'pickup'
            })
        });
        const data = await res.json();
        const ongkirFinal = (data.ongkir && data.ongkir > 0)
            ? Math.round(Number(data.ongkir))
            : ONGKIR_ARMADA_DEFAULT;
        tampilkanOngkir(ongkirFinal);
    } catch (e) {
        tampilkanOngkir(ONGKIR_ARMADA_DEFAULT);
    }
}

function getEkspedisiFee(val) {
    if (val === 'jne') return 25000;
    if (val === 'jnt') return 23000;
    if (val === 'sicepat') return 20000;
    return 23000;
}

function updatePengiriman(radio) {
    const selectedAlamat   = document.querySelector('[name="alamat_id"]:checked');
    const ekspedisiOptions = document.getElementById('ekspedisiOptions');
    if (radio.value === 'ekspedisi') {
        if (ekspedisiOptions) ekspedisiOptions.style.display = 'grid';
        const activeEkspedisi = document.querySelector('[name="ekspedisi"]:checked')?.value || 'jnt';
        tampilkanOngkir(getEkspedisiFee(activeEkspedisi));
    } else {
        if (ekspedisiOptions) ekspedisiOptions.style.display = 'none';
        if (selectedAlamat) {
            hitungOngkir(selectedAlamat.getAttribute('data-kota'), radio.value);
        } else {
            tampilkanOngkir(ONGKIR_ARMADA_DEFAULT);
        }
    }
}

document.querySelectorAll('[name="alamat_id"]').forEach(r => {
    r.addEventListener('change', () => {
        const jenis = document.querySelector('[name="jenis_pengiriman"]:checked')?.value;
        if (jenis) updatePengiriman({value: jenis});
    });
});

document.querySelectorAll('[name="ekspedisi"]').forEach(r => {
    r.addEventListener('change', () => {
        const jenis = document.querySelector('[name="jenis_pengiriman"]:checked')?.value;
        if (jenis === 'ekspedisi') tampilkanOngkir(getEkspedisiFee(r.value));
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const activeJenis = document.querySelector('[name="jenis_pengiriman"]:checked');
    if (activeJenis) updatePengiriman(activeJenis);
});

// ── Voucher ──────────────────────────────────────────────────────────
async function terapkanVoucher() {
    const kode = document.getElementById('inputKodeVoucher').value.trim().toUpperCase();
    if (!kode) {
        showVoucherPesan('Masukkan kode voucher terlebih dahulu.', 'error');
        return;
    }
    showVoucherPesan('⏳ Memeriksa voucher...', 'info');
    try {
        const res = await fetch('{{ route("checkout.voucher") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ kode, subtotal })
        });
        const data = await res.json();
        if (!data.valid) {
            showVoucherPesan('❌ ' + data.pesan, 'error');
            return;
        }
        // Terapkan diskon
        diskonVoucher = Math.round(data.diskon);
        document.getElementById('hiddenKodeVoucher').value = kode;
        document.getElementById('diskonVoucherVal').value  = diskonVoucher;
        document.getElementById('voucherDisplay').textContent = '- Rp ' + diskonVoucher.toLocaleString('id-ID');
        updateTotal();
        // Tampilkan status aktif
        document.getElementById('voucherAktifLabel').textContent = '✅ ' + data.nama + ' diterapkan!';
        document.getElementById('voucherAktifInfo').textContent  =
            'Kode: ' + kode + ' · Hemat Rp ' + diskonVoucher.toLocaleString('id-ID');
        document.getElementById('voucherAktifBox').style.display = 'block';
        document.getElementById('voucherInputBox').style.display = 'none';
        showVoucherPesan('', 'none');
    } catch (e) {
        showVoucherPesan('❌ Gagal memeriksa voucher. Coba lagi.', 'error');
    }
}

function hapusVoucher() {
    diskonVoucher = 0;
    document.getElementById('hiddenKodeVoucher').value = '';
    document.getElementById('diskonVoucherVal').value  = 0;
    document.getElementById('inputKodeVoucher').value  = '';
    document.getElementById('voucherDisplay').textContent = '- Rp 0';
    document.getElementById('voucherAktifBox').style.display = 'none';
    document.getElementById('voucherInputBox').style.display = 'block';
    updateTotal();
}

function showVoucherPesan(msg, type) {
    const el = document.getElementById('voucherPesan');
    if (!msg) { el.style.display = 'none'; return; }
    el.style.display = 'block';
    el.style.color   = type === 'error' ? '#c03030' : type === 'info' ? 'var(--clay)' : 'var(--moss)';
    el.textContent   = msg;
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('inputKodeVoucher')?.addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); terapkanVoucher(); }
    });
});
</script>
@endpush
