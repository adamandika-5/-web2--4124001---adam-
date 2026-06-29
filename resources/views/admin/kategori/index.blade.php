@extends('layouts.admin')
@section('title','Manajemen Kategori')
@section('page_title','Kategori & Sub-Kategori')
@section('breadcrumb','Inventaris › Kategori')

@section('content')
<div class="adm-kategori-grid">

    {{-- ── KATEGORI UTAMA ── --}}
    <div>
        <div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);overflow:hidden;border:1px solid rgba(176,139,110,.07)">
            <div style="padding:16px 20px;border-bottom:1px solid rgba(176,139,110,.09);display:flex;align-items:center;justify-content:space-between">
                <div style="font-family:var(--fd);font-size:15.5px;font-weight:500;color:var(--soil)">Kategori Utama</div>
                <span style="font-size:13px;color:var(--clay)">{{ $kategoris->count() }} kategori</span>
            </div>
            <div class="table-scroll-wrap">
            <table class="data-tbl" style="min-width:420px">
                <thead>
                    <tr>
                        <th>Nama</th><th>Ikon</th><th>Produk</th><th>Status</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{--
                      Eager loading di controller:
                      Kategori::withCount(['produk' => fn($q) => $q->where('aktif', true)])
                               ->with('subKategori')
                               ->orderBy('urutan')
                               ->get()
                    --}}
                    @forelse($kategoris as $kat)
                    <tr>
                        <td>
                            <div style="font-weight:700;font-size:13px;color:var(--soil)">{{ $kat->nama }}</div>
                            <div style="font-size:11px;color:var(--clay)">{{ $kat->slug }}</div>
                        </td>
                        <td style="font-size:22px;text-align:center">{{ $kat->ikon ?? '📦' }}</td>
                        <td>
                            <span style="font-weight:700;color:{{ $kat->produk_count > 0 ? 'var(--moss)' : 'var(--concrete)' }}">
                                {{ $kat->produk_count }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.kategori.update', $kat->id) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="aktif" value="{{ $kat->aktif ? 0 : 1 }}">
                                <input type="hidden" name="nama" value="{{ $kat->nama }}">
                                <button type="submit" class="toggle {{ $kat->aktif ? 'on' : 'off' }}"></button>
                            </form>
                        </td>
                        <td>
                            <div class="act-btns">
                                <button onclick="editKategori({{ $kat->id }},'{{ addslashes($kat->nama) }}','{{ $kat->ikon }}','{{ $kat->warna }}')" class="act-btn" title="Edit">
                                    <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                @if($kat->produk_count === 0)
                                <form action="{{ route('admin.kategori.destroy', $kat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ addslashes($kat->nama) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="act-btn danger">
                                        <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--clay)">Belum ada kategori</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>{{-- /table-scroll-wrap --}}
        </div>
    </div>

    {{-- ── KANAN: FORM + SUB-KATEGORI ── --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- Form Tambah/Edit Kategori --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:22px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
            <div style="font-family:var(--fd);font-size:15.5px;font-weight:500;color:var(--soil);margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid var(--sand)" id="formKatTitle">
                + Tambah Kategori Baru
            </div>
            <form id="formKategori" action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formKatMethod" value="POST">
                <input type="hidden" name="kategori_id" id="editKatId" value="">

                <div class="profil-form-2col">
                    <div class="form-grp">
                        <label class="form-lbl">Nama Kategori *</label>
                        <input class="form-inp" type="text" name="nama" id="inputKatNama" placeholder="Material Dasar" required>
                    </div>
                    <div class="form-grp">
                        <label class="form-lbl">Ikon</label>
                        <input class="form-inp" type="text" name="ikon" id="inputKatIkon" placeholder="🧱" maxlength="4" style="text-align:center;font-size:18px">
                    </div>
                </div>

                <div class="profil-form-2col">
                    <div class="form-grp">
                        <label class="form-lbl">Warna Swatch</label>
                        <input class="form-inp" type="text" name="warna" id="inputKatWarna" placeholder="#8A8A80">
                    </div>
                    <div class="form-grp">
                        <label class="form-lbl">Urutan</label>
                        <input class="form-inp" type="number" name="urutan" placeholder="0" min="0">
                    </div>
                </div>

                <div class="form-grp">
                    <label class="form-lbl">Deskripsi (opsional)</label>
                    <input class="form-inp" type="text" name="deskripsi" placeholder="Deskripsi singkat kategori">
                </div>

                <div style="display:flex;gap:10px">
                    <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">💾 Simpan</button>
                    <button type="button" onclick="resetFormKat()" class="btn btn-secondary btn-sm">Reset</button>
                </div>
            </form>
        </div>

        {{-- Sub-Kategori --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:22px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
            <div style="font-family:var(--fd);font-size:15.5px;font-weight:500;color:var(--soil);margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid var(--sand)">
                Sub-Kategori
            </div>

            {{-- Form tambah sub --}}
            <form action="{{ route('admin.sub-kategori.store') }}" method="POST" style="display:flex;gap:10px;margin-bottom:16px">
                @csrf
                <select name="kategori_id" class="form-inp" style="flex:1;padding:8px 12px;font-size:13px" required>
                    <option value="">Pilih kategori induk</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                    @endforeach
                </select>
                <input type="text" name="nama" class="form-inp" placeholder="Nama sub-kategori" required style="flex:1;padding:8px 12px;font-size:13px">
                <button type="submit" class="btn btn-primary btn-sm">+ Tambah</button>
            </form>

            {{-- List sub per kategori --}}
            <div style="display:flex;flex-direction:column;gap:10px;max-height:320px;overflow-y:auto">
                @foreach($kategoris as $kat)
                    @if($kat->subKategori->isNotEmpty())
                    <div>
                        <div style="font-size:11.5px;font-weight:700;color:var(--clay);letter-spacing:.05em;text-transform:uppercase;margin-bottom:6px;display:flex;align-items:center;gap:6px">
                            <span>{{ $kat->ikon ?? '📦' }}</span> {{ $kat->nama }}
                        </div>
                        <div style="display:flex;flex-wrap:wrap;gap:6px">
                            @foreach($kat->subKategori as $sub)
                            <div style="display:flex;align-items:center;gap:6px;padding:4px 10px 4px 12px;background:var(--oat);border-radius:20px;border:1px solid rgba(176,139,110,.15)">
                                <span style="font-size:13px;color:var(--soil)">{{ $sub->nama }}</span>
                                <form action="{{ route('admin.sub-kategori.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Hapus sub-kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background:none;border:none;cursor:pointer;color:var(--clay);font-size:12px;padding:0;line-height:1" onmouseover="this.style.color='#c03030'" onmouseout="this.style.color='var(--clay)'">✕</button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function editKategori(id, nama, ikon, warna) {
    document.getElementById('formKatTitle').textContent = 'Edit Kategori';
    document.getElementById('formKategori').action = `/admin/kategori/${id}`;
    document.getElementById('formKatMethod').value = 'PUT';
    document.getElementById('editKatId').value = id;
    document.getElementById('inputKatNama').value = nama;
    document.getElementById('inputKatIkon').value = ikon || '';
    document.getElementById('inputKatWarna').value = warna || '';
    document.getElementById('inputKatNama').focus();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetFormKat() {
    document.getElementById('formKatTitle').textContent = '+ Tambah Kategori Baru';
    document.getElementById('formKategori').action = '{{ route("admin.kategori.store") }}';
    document.getElementById('formKatMethod').value = 'POST';
    document.getElementById('editKatId').value = '';
    document.getElementById('formKategori').reset();
}
</script>
@endpush