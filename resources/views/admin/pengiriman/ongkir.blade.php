@extends('layouts.admin')
@section('title', 'Zona Ongkir')
@section('page_title', 'Manajemen Zona Ongkir')
@section('breadcrumb', 'Layanan › Pengiriman › Zona Ongkir')

@section('content')

<div style="display:grid;grid-template-columns:1fr 380px;gap:22px">

    {{-- Tabel zona --}}
    <div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07);overflow:hidden">
        <div style="padding:18px 22px;border-bottom:1px solid rgba(176,139,110,.07)">
            <div style="font-family:var(--fd);font-size:16px;font-weight:500;color:var(--soil)">Zona Ongkir Armada</div>
            <div style="font-size:12.5px;color:var(--clay);margin-top:4px">
                Tarif pengiriman menggunakan kendaraan sendiri (pickup, engkel, truk)
            </div>
        </div>
        <div style="overflow-x:auto">
            <table style="width:100%;border-collapse:collapse;font-size:12.5px">
                <thead>
                    <tr style="background:var(--oat)">
                        <th style="padding:10px 14px;text-align:left;color:var(--clay);font-weight:600">Kota/Kabupaten</th>
                        <th style="padding:10px 14px;text-align:left;color:var(--clay);font-weight:600">Zona</th>
                        <th style="padding:10px 14px;text-align:right;color:var(--clay);font-weight:600">Pickup</th>
                        <th style="padding:10px 14px;text-align:right;color:var(--clay);font-weight:600">Engkel</th>
                        <th style="padding:10px 14px;text-align:right;color:var(--clay);font-weight:600">Truk</th>
                        <th style="padding:10px 14px;text-align:center;color:var(--clay);font-weight:600">Armada</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($zonas as $zona)
                    <tr style="border-top:1px solid rgba(176,139,110,.06)">
                        <td style="padding:10px 14px">
                            <div style="font-weight:600;color:var(--soil)">{{ $zona->kota }}</div>
                            @if($zona->kabupaten)
                            <div style="font-size:11px;color:var(--clay)">Kab. {{ $zona->kabupaten }}</div>
                            @endif
                            <div style="font-size:11px;color:var(--clay)">{{ $zona->provinsi }}</div>
                        </td>
                        <td style="padding:10px 14px">
                            <span style="padding:2px 8px;border-radius:99px;font-size:11px;font-weight:700;background:var(--oat);color:var(--soil)">
                                {{ $zona->zona }} ({{ $zona->jarak_km }} km)
                            </span>
                        </td>
                        <td style="padding:10px 14px;text-align:right;font-weight:600;color:var(--soil)">
                            Rp {{ number_format($zona->tarif_pickup, 0, ',', '.') }}
                        </td>
                        <td style="padding:10px 14px;text-align:right;color:var(--clay)">
                            {{ $zona->tarif_engkel ? 'Rp '.number_format($zona->tarif_engkel,0,',','.') : '-' }}
                        </td>
                        <td style="padding:10px 14px;text-align:right;color:var(--clay)">
                            {{ $zona->tarif_truk ? 'Rp '.number_format($zona->tarif_truk,0,',','.') : '-' }}
                        </td>
                        <td style="padding:10px 14px;text-align:center">
                            <span style="font-size:16px">{{ $zona->tersedia_armada ? '✅' : '❌' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding:32px;text-align:center;color:var(--clay)">
                            Belum ada zona ongkir. Tambahkan zona di form kanan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:12px 18px">{{ $zonas->links() }}</div>
    </div>

    {{-- Form tambah/edit zona --}}
    <div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07);padding:22px;height:fit-content;position:sticky;top:78px">
        <div style="font-family:var(--fd);font-size:16px;font-weight:500;color:var(--soil);margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid var(--sand)">
            ➕ Tambah / Update Zona
        </div>
        <form action="{{ route('admin.pengiriman.ongkir.simpan') }}" method="POST">
            @csrf
            <div class="form-grp">
                <label class="form-lbl">Kota *</label>
                <input class="form-inp" type="text" name="kota" required placeholder="cth: Pandaan">
            </div>
            <div class="form-grp">
                <label class="form-lbl">Kabupaten</label>
                <input class="form-inp" type="text" name="kabupaten" placeholder="cth: Pasuruan">
            </div>
            <div class="form-grp">
                <label class="form-lbl">Provinsi *</label>
                <input class="form-inp" type="text" name="provinsi" required value="Jawa Timur">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                <div class="form-grp">
                    <label class="form-lbl">Jarak (km) *</label>
                    <input class="form-inp" type="number" name="jarak_km" required min="0" step="0.1" placeholder="15">
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Zona *</label>
                    <select class="form-inp" name="zona">
                        <option value="zona-1">Zona 1 (&lt;15km)</option>
                        <option value="zona-2">Zona 2 (15-30km)</option>
                        <option value="zona-3">Zona 3 (30-60km)</option>
                        <option value="zona-4">Zona 4 (&gt;60km)</option>
                    </select>
                </div>
            </div>
            <div class="form-grp">
                <label class="form-lbl">Tarif Pickup (Rp) *</label>
                <input class="form-inp" type="number" name="tarif_pickup" required min="0" placeholder="100000">
            </div>
            <div class="form-grp">
                <label class="form-lbl">Tarif Engkel (Rp)</label>
                <input class="form-inp" type="number" name="tarif_engkel" min="0" placeholder="300000">
            </div>
            <div class="form-grp">
                <label class="form-lbl">Tarif Truk (Rp)</label>
                <input class="form-inp" type="number" name="tarif_truk" min="0" placeholder="600000">
            </div>
            <label style="display:flex;align-items:center;gap:8px;margin-bottom:18px;cursor:pointer">
                <input type="checkbox" name="tersedia_armada" value="1" checked
                       style="width:15px;height:15px;accent-color:var(--terracotta)">
                <span style="font-size:13px;color:var(--soil)">Tersedia untuk armada sendiri</span>
            </label>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                💾 Simpan Zona
            </button>
        </form>
    </div>

</div>

@endsection
