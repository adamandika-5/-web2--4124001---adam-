@extends('layouts.admin')
@section('title','Promo & Voucher')
@section('page_title','Promo & Voucher')
@section('breadcrumb','Pemasaran › Promo & Voucher')

@section('content')

{{-- Tab --}}
<div style="display:flex;gap:4px;background:var(--sand);border-radius:var(--r-md);padding:4px;margin-bottom:20px;width:fit-content">
    <button onclick="gantiPanel('promo',this)" id="btn-promo"
            style="padding:8px 20px;border-radius:10px;font-size:13.5px;font-weight:700;border:none;background:#fff;color:var(--soil);cursor:pointer;font-family:var(--fb);box-shadow:var(--sh-sm)">
        🏷️ Promo Banner
    </button>
    <button onclick="gantiPanel('voucher',this)" id="btn-voucher"
            style="padding:8px 20px;border-radius:10px;font-size:13.5px;font-weight:600;border:none;background:transparent;color:var(--clay);cursor:pointer;font-family:var(--fb)">
        🎫 Kode Voucher
    </button>
</div>

{{-- ── PANEL PROMO ── --}}
<div id="panel-promo">
<div style="display:grid;grid-template-columns:2fr 1fr;gap:16px">

    <div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);overflow:hidden;border:1px solid rgba(176,139,110,.07)">
        <div style="padding:14px 20px;border-bottom:1px solid rgba(176,139,110,.09);display:flex;align-items:center;justify-content:space-between">
            <div style="font-family:var(--fd);font-size:15.5px;font-weight:500;color:var(--soil)">Daftar Promo</div>
        </div>
        <table class="data-tbl">
            <thead>
                <tr><th>Nama</th><th>Tipe</th><th>Nilai</th><th>Periode</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($promos as $promo)
                <tr>
                    <td>
                        <div style="font-weight:700;font-size:13px;color:var(--soil)">{{ $promo->nama }}</div>
                        @if($promo->label)<div style="font-size:11px;color:var(--clay)">{{ $promo->label }}</div>@endif
                    </td>
                    <td><span class="badge" style="background:rgba(176,139,110,.1);color:var(--clay);font-size:11px">{{ ucfirst($promo->tipe) }}</span></td>
                    <td style="font-weight:700;color:var(--terracotta)">
                        {{ $promo->tipe==='persentase' ? $promo->nilai.'%' : 'Rp '.number_format($promo->nilai,0,',','.') }}
                    </td>
                    <td style="font-size:12px;color:var(--clay)">
                        {{ $promo->mulai_at->format('d M') }} – {{ $promo->berakhir_at->format('d M Y') }}
                    </td>
                    <td>
                        @php $aktif = $promo->aktif && $promo->berakhir_at->isFuture(); @endphp
                        <span class="status-pill {{ $aktif ? 's-lunas' : 's-pending' }}">
                            {{ $aktif ? 'Aktif' : ($promo->berakhir_at->isPast() ? 'Berakhir' : 'Nonaktif') }}
                        </span>
                    </td>
                    <td>
                        <div class="act-btns">
                            <a href="{{ route('admin.promo.edit', $promo->id) }}" class="act-btn">
                                <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form action="{{ route('admin.promo.destroy', $promo->id) }}" method="POST" onsubmit="return confirm('Hapus promo ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn danger">
                                    <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--clay)">Belum ada promo</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Form Promo --}}
    <div style="background:#fff;border-radius:var(--r-lg);padding:22px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
        <div style="font-family:var(--fd);font-size:15.5px;font-weight:500;color:var(--soil);margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid var(--sand)">
            + Promo Baru
        </div>
        <form action="{{ route('admin.promo.store') }}" method="POST">
            @csrf
            <div class="form-grp">
                <label class="form-lbl">Nama Promo *</label>
                <input class="form-inp" type="text" name="nama" placeholder="Flash Sale Semen" required>
            </div>
            <div class="form-grp">
                <label class="form-lbl">Label Banner</label>
                <input class="form-inp" type="text" name="label" placeholder="Promo Akhir Pekan">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                <div class="form-grp">
                    <label class="form-lbl">Tipe *</label>
                    <select class="form-inp" name="tipe" required>
                        <option value="persentase">Persentase (%)</option>
                        <option value="nominal">Nominal (Rp)</option>
                        <option value="gratis_ongkir">Gratis Ongkir</option>
                    </select>
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Nilai *</label>
                    <input class="form-inp" type="number" name="nilai" placeholder="15 atau 50000" required min="0">
                </div>
            </div>
            <div class="form-grp">
                <label class="form-lbl">Min. Belanja (Rp)</label>
                <input class="form-inp" type="number" name="min_belanja" value="0" min="0">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                <div class="form-grp">
                    <label class="form-lbl">Mulai *</label>
                    <input class="form-inp" type="datetime-local" name="mulai_at" required>
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Berakhir *</label>
                    <input class="form-inp" type="datetime-local" name="berakhir_at" required>
                </div>
            </div>
            <label style="display:flex;align-items:center;gap:8px;margin-bottom:16px;font-size:13.5px;color:var(--soil);cursor:pointer">
                <input type="checkbox" name="aktif" value="1" checked style="accent-color:var(--terracotta)">
                Aktifkan promo
            </label>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">💾 Simpan Promo</button>
        </form>
    </div>
</div>
</div>

{{-- ── PANEL VOUCHER ── --}}
<div id="panel-voucher" style="display:none">
<div style="display:grid;grid-template-columns:2fr 1fr;gap:16px">

    <div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);overflow:hidden;border:1px solid rgba(176,139,110,.07)">
        <div style="padding:14px 20px;border-bottom:1px solid rgba(176,139,110,.09)">
            <div style="font-family:var(--fd);font-size:15.5px;font-weight:500;color:var(--soil)">Daftar Voucher</div>
        </div>
        <table class="data-tbl">
            <thead>
                <tr><th>Kode</th><th>Nama</th><th>Diskon</th><th>Min. Belanja</th><th>Kuota</th><th>Berlaku</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($vouchers as $v)
                <tr>
                    <td>
                        <code style="background:var(--oat);padding:3px 8px;border-radius:6px;font-size:13px;font-weight:700;color:var(--soil);letter-spacing:.06em">
                            {{ $v->kode }}
                        </code>
                    </td>
                    <td style="font-size:13px;color:var(--soil)">{{ $v->nama }}</td>
                    <td style="font-weight:700;color:var(--terracotta)">
                        {{ $v->tipe==='persentase' ? $v->nilai.'%' : 'Rp '.number_format($v->nilai,0,',','.') }}
                        @if($v->maks_diskon)<div style="font-size:11px;color:var(--clay)">Maks Rp {{ number_format($v->maks_diskon,0,',','.') }}</div>@endif
                    </td>
                    <td style="font-size:12.5px;color:var(--clay)">Rp {{ number_format($v->min_belanja,0,',','.') }}</td>
                    <td>
                        <div style="font-size:13px;font-weight:700;color:var(--soil)">{{ $v->terpakai }} / {{ $v->kuota ?? '∞' }}</div>
                        @if($v->kuota)
                        <div style="width:60px;height:4px;background:var(--sand);border-radius:2px;margin-top:3px;overflow:hidden">
                            <div style="height:100%;width:{{ min(100,($v->terpakai/$v->kuota)*100) }}%;background:var(--terracotta);border-radius:2px"></div>
                        </div>
                        @endif
                    </td>
                    <td style="font-size:12px;color:var(--clay)">
                        @if($v->berlaku_sampai)
                            s/d {{ $v->berlaku_sampai->format('d M Y') }}
                        @else
                            Tidak terbatas
                        @endif
                    </td>
                    <td>
                        @php $valid = $v->aktif && (!$v->berlaku_sampai || $v->berlaku_sampai->isFuture()) && (!$v->kuota || $v->terpakai < $v->kuota); @endphp
                        <span class="status-pill {{ $valid ? 's-lunas' : 's-pending' }}">
                            {{ $valid ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </td>
                    <td>
                        <div class="act-btns">
                            <form action="{{ route('admin.voucher.destroy', $v->id) }}" method="POST" onsubmit="return confirm('Hapus voucher {{ $v->kode }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn danger">
                                    <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;padding:32px;color:var(--clay)">Belum ada voucher</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Form Voucher --}}
    <div style="background:#fff;border-radius:var(--r-lg);padding:22px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
        <div style="font-family:var(--fd);font-size:15.5px;font-weight:500;color:var(--soil);margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid var(--sand)">
            + Voucher Baru
        </div>
        <form action="{{ route('admin.voucher.store') }}" method="POST">
            @csrf
            <div class="form-grp">
                <label class="form-lbl">Kode Voucher *</label>
                <div style="display:flex;gap:8px">
                    <input class="form-inp" type="text" name="kode" id="voucherKode"
                           placeholder="SINARALAM50" required
                           style="text-transform:uppercase;font-family:monospace;font-weight:700;letter-spacing:.06em">
                    <button type="button" onclick="generateKode()"
                            class="btn btn-secondary btn-sm" style="flex-shrink:0">Generate</button>
                </div>
            </div>
            <div class="form-grp">
                <label class="form-lbl">Nama Voucher *</label>
                <input class="form-inp" type="text" name="nama" placeholder="Diskon Member Baru" required>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                <div class="form-grp">
                    <label class="form-lbl">Tipe *</label>
                    <select class="form-inp" name="tipe" required>
                        <option value="nominal">Nominal (Rp)</option>
                        <option value="persentase">Persentase (%)</option>
                        <option value="gratis_ongkir">Gratis Ongkir</option>
                    </select>
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Nilai *</label>
                    <input class="form-inp" type="number" name="nilai" placeholder="50000" required min="0">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                <div class="form-grp">
                    <label class="form-lbl">Min. Belanja (Rp)</label>
                    <input class="form-inp" type="number" name="min_belanja" value="0" min="0">
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Maks. Diskon (Rp)</label>
                    <input class="form-inp" type="number" name="maks_diskon" placeholder="Kosongkan = unlimited" min="0">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                <div class="form-grp">
                    <label class="form-lbl">Kuota</label>
                    <input class="form-inp" type="number" name="kuota" placeholder="Kosongkan = unlimited" min="1">
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Maks/User</label>
                    <input class="form-inp" type="number" name="maks_per_user" value="1" min="1">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                <div class="form-grp">
                    <label class="form-lbl">Berlaku Mulai</label>
                    <input class="form-inp" type="date" name="berlaku_mulai">
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Berlaku Sampai</label>
                    <input class="form-inp" type="date" name="berlaku_sampai">
                </div>
            </div>
            <label style="display:flex;align-items:center;gap:8px;margin-bottom:16px;font-size:13.5px;color:var(--soil);cursor:pointer">
                <input type="checkbox" name="aktif" value="1" checked style="accent-color:var(--terracotta)">
                Aktifkan voucher
            </label>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">💾 Buat Voucher</button>
        </form>
    </div>
</div>
</div>

@endsection

@push('scripts')
<script>
function gantiPanel(panel, btn) {
    ['promo','voucher'].forEach(p => {
        document.getElementById('panel-'+p).style.display = p===panel ? 'block' : 'none';
    });
    ['btn-promo','btn-voucher'].forEach(b => {
        const el = document.getElementById(b);
        if (b === 'btn-'+panel) {
            el.style.background='#fff'; el.style.color='var(--soil)'; el.style.fontWeight='700';
            el.style.boxShadow='var(--sh-sm)';
        } else {
            el.style.background='transparent'; el.style.color='var(--clay)'; el.style.fontWeight='600';
            el.style.boxShadow='none';
        }
    });
}

function generateKode() {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    let kode = 'SA';
    for (let i=0; i<6; i++) kode += chars[Math.floor(Math.random()*chars.length)];
    document.getElementById('voucherKode').value = kode;
}
</script>
@endpush