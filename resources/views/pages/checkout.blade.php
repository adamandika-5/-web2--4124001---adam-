@extends('layouts.app')
@section('title', 'Checkout')

@section('content')

{{-- Stepper --}}
<div style="background:#fff;border-bottom:1px solid rgba(176,139,110,.12);padding:14px 48px">
    <div style="max-width:1200px;margin:0 auto;display:flex;align-items:center;gap:16px;font-size:13.5px;font-weight:600">
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

<div style="max-width:1200px;margin:0 auto;padding:32px 48px;display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start">

    {{-- ── KIRI ── --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- Alamat Pengiriman --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:24px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08)">
            <div style="font-family:var(--fd);font-size:17px;font-weight:500;color:var(--soil);margin-bottom:18px;display:flex;align-items:center;justify-content:space-between">
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
                        <input type="radio" name="alamat_id" value="{{ $alamat->id }}"
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
            <div style="font-family:var(--fd);font-size:17px;font-weight:500;color:var(--soil);margin-bottom:18px">
                🚚 Metode Pengiriman
            </div>

            <div style="display:flex;flex-direction:column;gap:10px">
                {{-- Armada --}}
                <label style="display:flex;gap:14px;padding:16px;border:2px solid var(--sand);border-radius:var(--r-lg);cursor:pointer;transition:border-color .2s"
                       onmouseover="this.style.borderColor='var(--terracotta)'" onmouseout="if(!this.querySelector('input').checked)this.style.borderColor='var(--sand)'">
                    <input type="radio" name="jenis_pengiriman" value="armada" onchange="updatePengiriman(this)"
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

                {{-- Ekspedisi --}}
                <div style="border:2px solid var(--sand);border-radius:var(--r-lg);overflow:hidden">
                    <label style="display:flex;gap:14px;padding:16px;cursor:pointer;background:#fff;transition:background .2s"
                           onmouseover="this.style.background='rgba(198,107,61,.03)'" onmouseout="this.style.background='#fff'">
                        <input type="radio" name="jenis_pengiriman" value="ekspedisi" checked onchange="updatePengiriman(this)"
                               style="accent-color:var(--terracotta);margin-top:3px;flex-shrink:0">
                        <div>
                            <div style="font-size:14px;font-weight:700;color:var(--soil);margin-bottom:3px">📦 Ekspedisi</div>
                            <div style="font-size:12.5px;color:var(--clay)">J&T Express · JNE · SiCepat — barang ringan & sedang</div>
                        </div>
                    </label>

                    {{-- Sub-pilihan ekspedisi --}}
                    <div id="ekspedisiOptions" style="padding:0 16px 16px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px">
                        @foreach([
                            ['jnt','J&T Express','2-4 hari'],
                            ['jne','JNE Regular','2-5 hari'],
                            ['sicepat','SiCepat HALU','1-3 hari'],
                        ] as [$val, $nama, $est])
                        <label style="display:flex;flex-direction:column;gap:4px;padding:11px;border:1.5px solid var(--sand);border-radius:var(--r-md);cursor:pointer;transition:all .2s;text-align:center"
                               onmouseover="this.style.borderColor='var(--terracotta)'" onmouseout="if(!this.querySelector('input').checked)this.style.borderColor='var(--sand)'">
                            <input type="radio" name="ekspedisi" value="{{ $val }}" {{ $val==='jnt'?'checked':'' }}
                                   style="display:none" onchange="this.closest('label').style.borderColor='var(--terracotta)'">
                            <span style="font-size:13px;font-weight:700;color:var(--soil)">{{ $nama }}</span>
                            <span style="font-size:11px;color:var(--clay)">{{ $est }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Metode Pembayaran --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:24px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08)">
            <div style="font-family:var(--fd);font-size:17px;font-weight:500;color:var(--soil);margin-bottom:18px">
                💳 Metode Pembayaran
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                @foreach([
                    ['transfer_bank','🏦','Transfer Bank','BCA, BNI, Mandiri, BRI'],
                    ['qris','📱','QRIS','Scan & bayar semua e-wallet'],
                    ['cod','💵','COD (Bayar di Tempat)','Khusus area Pasuruan'],
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

        {{-- Input tersembunyi --}}
        <input type="hidden" name="ongkir" id="ongkirVal" value="0">
    </div>

    {{-- ── KANAN: RINGKASAN ── --}}
    <div style="position:sticky;top:90px">

        {{-- Ringkasan Item --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:20px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08);margin-bottom:14px">
            <div style="font-size:13.5px;font-weight:700;color:var(--soil);margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--sand)">
                Ringkasan Pesanan ({{ \Cart::count() }} produk)
            </div>
            <div style="display:flex;flex-direction:column;gap:8px;max-height:180px;overflow-y:auto;margin-bottom:14px">
                @foreach(\Cart::getContent() as $item)
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
            @php $subtotal = \Cart::getTotal(); @endphp
            <div style="border-top:1px solid var(--sand);padding-top:12px;display:flex;flex-direction:column;gap:8px">
                <div style="display:flex;justify-content:space-between;font-size:13px">
                    <span style="color:var(--clay)">Subtotal</span>
                    <span style="font-weight:600">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px">
                    <span style="color:var(--clay)">Ongkos Kirim</span>
                    <span id="ongkirDisplay" style="font-weight:600;color:var(--ochre)">Pilih pengiriman</span>
                </div>
                <div style="border-top:2px solid var(--sand);padding-top:10px;display:flex;justify-content:space-between;margin-top:4px">
                    <span style="font-size:15px;font-weight:700;color:var(--soil)">Total</span>
                    <span id="totalDisplay" style="font-family:var(--fd);font-size:20px;font-weight:700;color:var(--terracotta)">
                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;font-size:15px;padding:14px">
            ✓ Buat Pesanan
        </button>
        <div style="text-align:center;margin-top:10px;font-size:12px;color:var(--clay)">🔒 Transaksi aman & terenkripsi</div>

        <a href="{{ route('keranjang.index') }}" style="display:block;text-align:center;margin-top:12px;font-size:13px;color:var(--terracotta);font-weight:600;text-decoration:none">
            ← Kembali ke keranjang
        </a>
    </div>

</div>
</form>

@endsection

@push('scripts')
<script>
const subtotal = {{ \Cart::getTotal() }};

async function hitungOngkir(kota, jenis) {
    if (!kota || jenis !== 'armada') return;
    const res = await fetch('{{ route("checkout.ongkir") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ kota, jenis_kendaraan: 'pickup' })
    });
    const data = await res.json();
    if (data.ongkir > 0) {
        document.getElementById('ongkirVal').value = data.ongkir;
        document.getElementById('ongkirDisplay').textContent = 'Rp ' + Number(data.ongkir).toLocaleString('id-ID');
        document.getElementById('ongkirDisplay').style.color = 'var(--soil)';
        document.getElementById('totalDisplay').textContent = 'Rp ' + (subtotal + data.ongkir).toLocaleString('id-ID');
    }
}

function updatePengiriman(radio) {
    const selectedAlamat = document.querySelector('[name="alamat_id"]:checked');
    if (radio.value === 'ekspedisi') {
        document.getElementById('ongkirDisplay').textContent = 'Dihitung saat konfirmasi';
        document.getElementById('ongkirDisplay').style.color = 'var(--ochre)';
    } else {
        if (selectedAlamat) {
            const kota = selectedAlamat.closest('label').querySelector('[style*="kota"]')?.textContent;
            hitungOngkir(kota, radio.value);
        }
    }
}

document.querySelectorAll('[name="alamat_id"]').forEach(r => {
    r.addEventListener('change', () => {
        const jenis = document.querySelector('[name="jenis_pengiriman"]:checked')?.value;
        if (jenis === 'armada') updatePengiriman({value:'armada'});
    });
});
</script>
@endpush