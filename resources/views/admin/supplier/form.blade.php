@extends('layouts.admin')
@section('title', isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier')
@section('page_title', isset($supplier) ? 'Edit Supplier' : 'Tambah Supplier')
@section('breadcrumb', 'Layanan › Supplier › ' . (isset($supplier) ? 'Edit' : 'Tambah'))

@section('content')
<div style="max-width:{{ isset($supplier) ? '960px' : '680px' }}">

    {{-- ══════════ FORM SUPPLIER ══════════ --}}
    <div style="background:#fff;border-radius:var(--r-lg);padding:28px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
        <div style="font-family:var(--fd);font-size:18px;font-weight:500;color:var(--soil);margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid var(--sand)">
            {{ isset($supplier) ? 'Edit Supplier: '.$supplier->nama : 'Tambah Supplier Baru' }}
        </div>

        <form action="{{ isset($supplier) ? route('admin.supplier.update', $supplier->id) : route('admin.supplier.store') }}"
              method="POST">
            @csrf
            @if(isset($supplier)) @method('PUT') @endif

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div class="form-grp" style="grid-column:1/-1">
                    <label class="form-lbl">Nama Supplier / Perusahaan *</label>
                    <input class="form-inp {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                           type="text" name="nama"
                           value="{{ old('nama', $supplier->nama ?? '') }}"
                           placeholder="PT. Semen Indonesia" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-grp">
                    <label class="form-lbl">Nama Kontak / PIC</label>
                    <input class="form-inp" type="text" name="kontak"
                           value="{{ old('kontak', $supplier->kontak ?? '') }}"
                           placeholder="Budi Santoso">
                </div>

                <div class="form-grp">
                    <label class="form-lbl">No. Telepon</label>
                    <input class="form-inp" type="tel" name="telepon"
                           value="{{ old('telepon', $supplier->telepon ?? '') }}"
                           placeholder="081234567890">
                </div>

                <div class="form-grp">
                    <label class="form-lbl">Email</label>
                    <input class="form-inp {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           type="email" name="email"
                           value="{{ old('email', $supplier->email ?? '') }}"
                           placeholder="supplier@email.com">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-grp">
                    <label class="form-lbl">Kota</label>
                    <input class="form-inp" type="text" name="kota"
                           value="{{ old('kota', $supplier->kota ?? '') }}"
                           placeholder="Gresik">
                </div>

                <div class="form-grp" style="grid-column:1/-1">
                    <label class="form-lbl">Alamat Lengkap</label>
                    <textarea class="form-inp" name="alamat" rows="2"
                              placeholder="Jl. Industri No. xx, Kawasan Industri...">{{ old('alamat', $supplier->alamat ?? '') }}</textarea>
                </div>

                <div class="form-grp" style="grid-column:1/-1">
                    <label class="form-lbl">Catatan Internal</label>
                    <textarea class="form-inp" name="catatan" rows="2"
                              placeholder="Catatan khusus, syarat pembayaran, dll...">{{ old('catatan', $supplier->catatan ?? '') }}</textarea>
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;padding:12px">
                    💾 {{ isset($supplier) ? 'Simpan Perubahan' : 'Tambah Supplier' }}
                </button>
                <a href="{{ route('admin.supplier.index') }}" class="btn btn-secondary" style="flex:1;justify-content:center">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- ══════════ BARANG DARI SUPPLIER ══════════ --}}
    @if(isset($supplier))
    <div style="background:#fff;border-radius:var(--r-lg);padding:28px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07);margin-top:20px">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;padding-bottom:14px;border-bottom:1px solid var(--sand)">
            <div style="font-family:var(--fd);font-size:18px;font-weight:500;color:var(--soil)">
                📦 Barang dari Supplier
            </div>
            <button type="button" onclick="document.getElementById('formTambahBarang').style.display = document.getElementById('formTambahBarang').style.display === 'none' ? 'block' : 'none'"
                    class="btn btn-primary btn-sm">
                + Tambah Barang
            </button>
        </div>

        {{-- Form Tambah Barang --}}
        <div id="formTambahBarang" style="display:none;background:var(--oat);border-radius:var(--r-md);padding:18px;margin-bottom:18px;border:1px solid rgba(176,139,110,.1)">
            <div style="font-size:14px;font-weight:700;color:var(--soil);margin-bottom:12px">Tambah Barang Baru</div>
            <form action="{{ route('admin.supplier.barang.store', $supplier->id) }}" method="POST">
                @csrf
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px">
                    <div class="form-grp" style="grid-column:1/-1">
                        <label class="form-lbl">Produk *</label>
                        <select name="produk_id" class="form-inp" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produks ?? [] as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }} (Rp {{ number_format($p->harga, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-grp">
                        <label class="form-lbl">Harga Beli *</label>
                        <input class="form-inp" type="number" name="harga_beli" min="0" step="100" required placeholder="0">
                    </div>
                    <div class="form-grp">
                        <label class="form-lbl">Satuan *</label>
                        <input class="form-inp" type="text" name="satuan" value="pcs" required maxlength="30">
                    </div>
                    <div class="form-grp">
                        <label class="form-lbl">Min. Pembelian *</label>
                        <input class="form-inp" type="number" name="minimal_pembelian" value="1" min="1" required>
                    </div>
                    <div class="form-grp">
                        <label class="form-lbl">Lead Time (hari)</label>
                        <input class="form-inp" type="number" name="lead_time_hari" min="0" placeholder="opsional">
                    </div>
                    <div class="form-grp" style="grid-column:1/-1">
                        <label class="form-lbl">Catatan</label>
                        <input class="form-inp" type="text" name="catatan" maxlength="500" placeholder="opsional">
                    </div>
                </div>
                <div style="display:flex;gap:8px;margin-top:10px">
                    <button type="submit" class="btn btn-primary btn-sm">💾 Simpan Barang</button>
                    <button type="button" onclick="document.getElementById('formTambahBarang').style.display='none'"
                            class="btn btn-secondary btn-sm">Batal</button>
                </div>
            </form>
        </div>

        {{-- Tabel Daftar Barang --}}
        @if($supplier->barangSupplier->isEmpty())
            <div style="text-align:center;padding:36px;color:var(--clay);font-size:14px">
                <div style="font-size:36px;margin-bottom:8px">📭</div>
                Belum ada barang dari supplier ini.<br>
                Klik <strong>"+ Tambah Barang"</strong> untuk menambahkan.
            </div>
        @else
            <div style="overflow-x:auto">
                <table class="adm-table" style="width:100%">
                    <thead>
                        <tr>
                            <th style="min-width:160px">Produk</th>
                            <th style="text-align:right">Harga Beli</th>
                            <th style="text-align:right">Harga Jual</th>
                            <th style="text-align:right">Margin</th>
                            <th>Satuan</th>
                            <th style="text-align:center">Min</th>
                            <th style="text-align:center">Lead</th>
                            <th style="text-align:center">Status</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($supplier->barangSupplier as $b)
                        @php
                            $hargaJual = $b->produk ? (float)($b->produk->harga_promo ?: $b->produk->harga) : 0;
                            $margin = $hargaJual - (float)$b->harga_beli;
                            $marginPersen = $hargaJual > 0 ? round(($margin / $hargaJual) * 100, 1) : 0;
                        @endphp
                        <tr id="row-{{ $b->id }}">
                            <td>
                                <div style="font-weight:600;color:var(--soil)">{{ $b->produk->nama ?? '-' }}</div>
                                @if($b->catatan)
                                    <div style="font-size:11px;color:var(--clay);margin-top:2px">{{ Str::limit($b->catatan, 40) }}</div>
                                @endif
                            </td>
                            <td style="text-align:right;font-weight:700;color:var(--soil)">
                                Rp {{ number_format($b->harga_beli, 0, ',', '.') }}
                            </td>
                            <td style="text-align:right;color:var(--clay)">
                                @if($hargaJual > 0)
                                    Rp {{ number_format($hargaJual, 0, ',', '.') }}
                                @else
                                    <span style="color:var(--ochre)">-</span>
                                @endif
                            </td>
                            <td style="text-align:right">
                                @if($margin > 0)
                                    <span style="color:var(--moss);font-weight:700">
                                        +Rp {{ number_format($margin, 0, ',', '.') }}
                                    </span>
                                    <div style="font-size:10px;color:var(--clay)">{{ $marginPersen }}%</div>
                                @elseif($margin < 0)
                                    <span style="color:#c03030;font-weight:700">
                                        -Rp {{ number_format(abs($margin), 0, ',', '.') }}
                                    </span>
                                @else
                                    <span style="color:var(--clay)">-</span>
                                @endif
                            </td>
                            <td>{{ $b->satuan }}</td>
                            <td style="text-align:center">{{ $b->minimal_pembelian }}</td>
                            <td style="text-align:center">{{ $b->lead_time_hari ? $b->lead_time_hari . ' hr' : '-' }}</td>
                            <td style="text-align:center">
                                <span class="status-pill {{ $b->aktif ? 'aktif' : 'nonaktif' }}">
                                    {{ $b->aktif ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td style="text-align:center">
                                <div class="act-btns">
                                    {{-- Toggle --}}
                                    <form action="{{ route('admin.supplier.barang.toggle', [$supplier->id, $b->id]) }}" method="POST" style="display:inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="act-btn" title="{{ $b->aktif ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                style="background:{{ $b->aktif ? 'rgba(176,139,110,.12)' : 'rgba(96,108,56,.12)' }};color:{{ $b->aktif ? '#a05a2c' : 'var(--moss)' }}">
                                            {{ $b->aktif ? '⏸' : '▶' }}
                                        </button>
                                    </form>
                                    {{-- Edit --}}
                                    <button type="button" class="act-btn" title="Edit"
                                            onclick="toggleEdit({{ $b->id }})">
                                        <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </button>
                                    {{-- Hapus --}}
                                    <form action="{{ route('admin.supplier.barang.destroy', [$supplier->id, $b->id]) }}" method="POST"
                                          style="display:inline" onsubmit="return confirm('Hapus barang ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="act-btn danger">
                                            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        {{-- Inline edit row --}}
                        <tr id="edit-{{ $b->id }}" style="display:none;background:var(--oat)">
                            <td colspan="9" style="padding:14px">
                                <form action="{{ route('admin.supplier.barang.update', [$supplier->id, $b->id]) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr 1fr;gap:8px;align-items:end">
                                        <div class="form-grp" style="margin-bottom:0">
                                            <label class="form-lbl" style="font-size:11px">Harga Beli</label>
                                            <input class="form-inp" type="number" name="harga_beli" value="{{ (int)$b->harga_beli }}" min="0" step="100" required>
                                        </div>
                                        <div class="form-grp" style="margin-bottom:0">
                                            <label class="form-lbl" style="font-size:11px">Satuan</label>
                                            <input class="form-inp" type="text" name="satuan" value="{{ $b->satuan }}" required maxlength="30">
                                        </div>
                                        <div class="form-grp" style="margin-bottom:0">
                                            <label class="form-lbl" style="font-size:11px">Min. Beli</label>
                                            <input class="form-inp" type="number" name="minimal_pembelian" value="{{ $b->minimal_pembelian }}" min="1" required>
                                        </div>
                                        <div class="form-grp" style="margin-bottom:0">
                                            <label class="form-lbl" style="font-size:11px">Lead Time</label>
                                            <input class="form-inp" type="number" name="lead_time_hari" value="{{ $b->lead_time_hari }}" min="0">
                                        </div>
                                        <div style="display:flex;gap:6px">
                                            <button type="submit" class="btn btn-primary btn-sm" style="padding:6px 14px;font-size:12px">💾</button>
                                            <button type="button" class="btn btn-secondary btn-sm" style="padding:6px 14px;font-size:12px" onclick="toggleEdit({{ $b->id }})">✖</button>
                                        </div>
                                    </div>
                                    <div class="form-grp" style="margin-top:8px;margin-bottom:0">
                                        <input class="form-inp" type="text" name="catatan" value="{{ $b->catatan }}" placeholder="Catatan (opsional)" maxlength="500">
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Ringkasan --}}
            @php
                $totalBarang = $supplier->barangSupplier->count();
                $barangAktif = $supplier->barangSupplier->where('aktif', true)->count();
                $avgMargin = $supplier->barangSupplier->avg(function($b) {
                    $hj = $b->produk ? (float)($b->produk->harga_promo ?: $b->produk->harga) : 0;
                    return $hj - (float)$b->harga_beli;
                });
            @endphp
            <div style="display:flex;gap:14px;margin-top:16px;flex-wrap:wrap">
                <div style="background:var(--oat);border-radius:var(--r-md);padding:10px 16px;font-size:12.5px;color:var(--clay)">
                    Total: <strong style="color:var(--soil)">{{ $totalBarang }} barang</strong>
                </div>
                <div style="background:var(--oat);border-radius:var(--r-md);padding:10px 16px;font-size:12.5px;color:var(--clay)">
                    Aktif: <strong style="color:var(--moss)">{{ $barangAktif }}</strong>
                </div>
                <div style="background:var(--oat);border-radius:var(--r-md);padding:10px 16px;font-size:12.5px;color:var(--clay)">
                    Rata-rata Margin: <strong style="color:{{ $avgMargin >= 0 ? 'var(--moss)' : '#c03030' }}">
                        Rp {{ number_format($avgMargin, 0, ',', '.') }}
                    </strong>
                </div>
            </div>
        @endif
    </div>
    @endif
</div>

@push('scripts')
<script>
function toggleEdit(id) {
    const row  = document.getElementById('edit-' + id);
    const show = row.style.display === 'none';
    // Hide all edit rows first
    document.querySelectorAll('[id^="edit-"]').forEach(r => r.style.display = 'none');
    if (show) row.style.display = 'table-row';
}
</script>
@endpush
@endsection
