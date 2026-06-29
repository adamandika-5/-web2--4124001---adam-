@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="profil-wrap">

    <div class="page-hdr" style="display:flex;justify-content:space-between;align-items:flex-end;gap:16px;margin-bottom:28px">
        <div>
            <div class="section-label">Akun Saya</div>
            <h1 class="section-title" style="font-size:clamp(26px,3vw,38px);margin-top:8px">Profil Saya</h1>
        </div>
        @if(in_array(auth()->user()->role, ['admin', 'super_admin']))
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm" style="margin-bottom:6px">
                ⬅ Kembali ke Dashboard Admin
            </a>
        @endif
    </div>

    {{-- Error/Success Alerts --}}
    @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:20px">
            <ul style="margin:0;padding-left:20px">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $isAdmin = in_array(auth()->user()->role, ['admin', 'super_admin']);
    @endphp

    @if($isAdmin)
    {{-- Admin: 1 kolom, center, max-width 800px --}}
    <div style="max-width:800px;margin:0 auto">
        <div style="display:flex;flex-direction:column;gap:24px">
    @else
    {{-- User biasa: 2 kolom --}}
    <div class="profil-layout">
        <div style="display:flex;flex-direction:column;gap:24px">
    @endif

            {{-- Card Informasi Profil --}}
            <div class="card">
                <div class="card-hdr">👤 Edit Profil</div>

                <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Avatar Display & Upload --}}
                    <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px">
                        <div style="width:64px;height:64px;border-radius:50%;background:var(--terracotta);display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px;font-weight:700;overflow:hidden;flex-shrink:0">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" style="width:100%;height:100%;object-fit:cover" alt="Avatar">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            @endif
                        </div>
                        <div style="flex:1">
                            <label class="form-lbl">Foto Profil (Avatar)</label>
                            <input type="file" name="avatar" style="font-size:13px;color:var(--clay)">
                        </div>
                    </div>

                    <div class="form-grp">
                        <label class="form-lbl">Nama Lengkap *</label>
                        <input type="text" name="name" class="form-inp"
                               value="{{ old('name', auth()->user()->name) }}" required
                               placeholder="Nama lengkap Anda">
                    </div>

                    <div class="form-grp">
                        <label class="form-lbl">Alamat Email *</label>
                        <input type="email" name="email" class="form-inp"
                               value="{{ old('email', auth()->user()->email) }}" required
                               placeholder="email@contoh.com">
                    </div>

                    <div class="form-grp">
                        <label class="form-lbl">Nomor Telepon</label>
                        <input type="text" name="telepon" class="form-inp"
                               value="{{ old('telepon', auth()->user()->telepon) }}"
                               placeholder="cth: 08123456789">
                    </div>

                    <div class="form-grp">
                        <label class="form-lbl">Role Akun</label>
                        <input type="text" class="form-inp"
                               value="{{ ucfirst(auth()->user()->role) }}" disabled
                               style="background:var(--oat);color:var(--clay);cursor:not-allowed">
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                        Simpan Perubahan Profil
                    </button>
                </form>
            </div>

            {{-- Card Ubah Password --}}
            <div class="card">
                <div class="card-hdr">🔑 Ubah Kata Sandi</div>

                <form action="{{ route('profil.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-grp">
                        <label class="form-lbl">Kata Sandi Sekarang *</label>
                        <input type="password" name="current_password" class="form-inp"
                               placeholder="••••••••" required>
                    </div>

                    <div class="form-grp">
                        <label class="form-lbl">Kata Sandi Baru * <span style="font-weight:400;color:var(--clay)">(Min. 8 karakter)</span></label>
                        <input type="password" name="password" class="form-inp"
                               placeholder="••••••••" required>
                    </div>

                    <div class="form-grp">
                        <label class="form-lbl">Konfirmasi Kata Sandi Baru *</label>
                        <input type="password" name="password_confirmation" class="form-inp"
                               placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn btn-secondary" style="width:100%;justify-content:center">
                        Perbarui Kata Sandi
                    </button>
                </form>
            </div>

        </div>{{-- /kolom kiri flex --}}

        {{-- KOLOM KANAN: hanya untuk user/pelanggan, bukan admin --}}
        @unless($isAdmin)
        <div style="display:flex;flex-direction:column;gap:24px">

            {{-- Daftar Alamat --}}
            <div class="card">
                <div class="card-hdr">📍 Alamat Pengiriman</div>

                @php
                    $alamatCollection = $alamats ?? collect();
                @endphp

                @forelse($alamatCollection as $alamat)
                    <div style="padding:16px;background:var(--oat);border-radius:var(--r-md);border:1.5px solid rgba(176,139,110,.12);margin-bottom:12px">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px">
                            <span style="font-size:11.5px;font-weight:700;color:var(--terracotta);text-transform:uppercase;background:rgba(198,107,61,.1);padding:2px 8px;border-radius:4px">
                                {{ $alamat->label }}
                            </span>
                            @if($alamat->is_utama)
                                <span style="font-size:11px;font-weight:700;color:var(--moss);background:rgba(22,163,74,.1);padding:2px 8px;border-radius:4px">
                                    Alamat Utama
                                </span>
                            @endif
                        </div>

                        <div style="font-size:13.5px;font-weight:700;color:var(--soil)">{{ $alamat->penerima }}</div>
                        <div style="font-size:12.5px;color:var(--clay)">📞 {{ $alamat->telepon }}</div>
                        <div style="font-size:13px;color:var(--soil-light);line-height:1.6;margin-top:6px">
                            {{ $alamat->alamat_lengkap }}<br>
                            {{ $alamat->kota }}, {{ $alamat->provinsi }}
                            @if($alamat->kode_pos) · {{ $alamat->kode_pos }} @endif
                        </div>

                        {{-- Tombol Lihat di Google Maps --}}
                        @php
                            $mapsUrl = null;
                            if ($alamat->link_google_maps) {
                                $mapsUrl = $alamat->link_google_maps;
                            } elseif ($alamat->latitude && $alamat->longitude) {
                                $mapsUrl = 'https://www.google.com/maps?q=' . $alamat->latitude . ',' . $alamat->longitude;
                            }
                        @endphp
                        @if($mapsUrl)
                            <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer"
                               style="display:inline-flex;align-items:center;gap:6px;margin-top:10px;padding:6px 12px;background:rgba(37,99,235,.08);color:#2563eb;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;text-decoration:none;border:1px solid rgba(37,99,235,.2);transition:all .2s"
                               onmouseover="this.style.background='rgba(37,99,235,.15)'"
                               onmouseout="this.style.background='rgba(37,99,235,.08)'">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
                                Lihat di Google Maps
                            </a>
                        @endif
                        @if($alamat->latitude && $alamat->longitude)
                            <div style="font-size:11px;color:var(--clay);margin-top:4px">
                                📐 {{ number_format($alamat->latitude, 6) }}, {{ number_format($alamat->longitude, 6) }}
                            </div>
                        @endif

                        {{-- Tombol Aksi (Jadikan Utama & Hapus Alamat) --}}
                        <div style="display:flex;align-items:center;gap:14px;margin-top:12px;flex-wrap:wrap">
                            @if(!$alamat->is_utama)
                                <form action="{{ route('profil.alamat.utama', $alamat->id) }}" method="POST" style="margin:0">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            style="background:none;border:1px solid rgba(22,163,74,.4);color:#16a34a;font-size:12px;font-weight:700;cursor:pointer;padding:4px 10px;border-radius:var(--r-sm);font-family:var(--fb);transition:all .2s;display:inline-flex;align-items:center;gap:4px"
                                            onmouseover="this.style.background='rgba(22,163,74,.1)'"
                                            onmouseout="this.style.background='none'">
                                        ⭐ Jadikan Utama
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('profil.alamat.delete', $alamat->id) }}" method="POST"
                                  style="margin:0"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        style="background:none;border:none;color:#c03030;font-size:12.5px;font-weight:700;cursor:pointer;padding:0;font-family:var(--fb);display:inline-flex;align-items:center;gap:4px">
                                    🗑️ Hapus Alamat
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:32px;color:var(--clay);font-size:13.5px">
                        Belum ada alamat pengiriman yang terdaftar.
                    </div>
                @endforelse
            </div>

            {{-- Form Tambah Alamat Baru --}}
            <div class="card">
                <div class="card-hdr">➕ Tambah Alamat Baru</div>

                <form action="{{ route('profil.alamat.store') }}" method="POST">
                    @csrf

                    <div class="form-grp">
                        <label class="form-lbl">Label Alamat * <span style="font-weight:400;color:var(--clay)">(cth: Rumah, Kantor)</span></label>
                        <input type="text" name="label" class="form-inp" required placeholder="Rumah / Kantor">
                    </div>

                    <div class="profil-form-2col">
                        <div class="form-grp">
                            <label class="form-lbl">Nama Penerima *</label>
                            <input type="text" name="penerima" class="form-inp" required placeholder="Nama penerima">
                        </div>
                        <div class="form-grp">
                            <label class="form-lbl">No. Telepon Penerima *</label>
                            <input type="text" name="telepon" class="form-inp" required placeholder="08xxx">
                        </div>
                    </div>

                    <div class="form-grp">
                        <label class="form-lbl">Alamat Lengkap * <span style="font-weight:400;color:var(--clay)">(Jalan, No Rumah, RT/RW)</span></label>
                        <textarea name="alamat_lengkap" class="form-inp" required rows="3"
                                  placeholder="Masukkan alamat lengkap..."
                                  style="resize:vertical"></textarea>
                    </div>

                    <div class="profil-form-2col">
                        <div class="form-grp">
                            <label class="form-lbl">Kota / Kabupaten *</label>
                            <input type="text" name="kota" class="form-inp" required placeholder="cth: Pasuruan">
                        </div>
                        <div class="form-grp">
                            <label class="form-lbl">Provinsi *</label>
                            <input type="text" name="provinsi" class="form-inp" required placeholder="cth: Jawa Timur">
                        </div>
                    </div>

                    <div class="profil-form-2col" style="margin-bottom:16px">
                        <div class="form-grp" style="margin-bottom:0">
                            <label class="form-lbl">Kode Pos</label>
                            <input type="text" name="kode_pos" class="form-inp" placeholder="67xxx">
                        </div>
                        <div style="display:flex;align-items:flex-end;padding-bottom:2px">
                            <label style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--soil);cursor:pointer">
                                <input type="checkbox" name="is_utama" value="1"
                                       style="width:15px;height:15px;accent-color:var(--terracotta);flex-shrink:0">
                                Jadikan Alamat Utama
                            </label>
                        </div>
                    </div>

                    {{-- ── Lokasi Maps (Opsional) ── --}}
                    <div style="border-top:1px dashed rgba(176,139,110,.25);margin:4px 0 16px;padding-top:16px">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--terracotta)" stroke-width="2.5"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
                            <span style="font-size:12.5px;font-weight:700;color:var(--soil)">Lokasi Maps <span style="font-weight:400;color:var(--clay);font-size:11.5px">(Opsional)</span></span>
                        </div>

                        <div style="background:rgba(37,99,235,.04);border:1px solid rgba(37,99,235,.12);border-radius:var(--r-sm);padding:10px 13px;margin-bottom:13px;font-size:12px;color:#2563eb;line-height:1.6">
                            💡 Cara mendapatkan koordinat: Buka <strong>Google Maps</strong> → Klik lokasi Anda → Klik kanan → <strong>"Apa yang ada di sini?"</strong> → Salin angka koordinat (lat, lng) di bagian bawah.
                        </div>

                        <div class="profil-form-2col">
                            <div class="form-grp" style="margin-bottom:0">
                                <label class="form-lbl">Latitude</label>
                                <input type="number" name="latitude" id="input-lat" class="form-inp"
                                       step="any" placeholder="cth: -7.5566"
                                       oninput="updateMapsPreview()">
                                @error('latitude') <div class="invalid-feedback" style="display:block">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-grp" style="margin-bottom:0">
                                <label class="form-lbl">Longitude</label>
                                <input type="number" name="longitude" id="input-lng" class="form-inp"
                                       step="any" placeholder="cth: 112.2384"
                                       oninput="updateMapsPreview()">
                                @error('longitude') <div class="invalid-feedback" style="display:block">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-grp" style="margin-top:12px">
                            <label class="form-lbl">Link Google Maps <span style="font-weight:400;color:var(--clay)">(paste link share dari Google Maps)</span></label>
                            <input type="url" name="link_google_maps" id="input-maps-link" class="form-inp"
                                   placeholder="https://maps.app.goo.gl/...">
                            @error('link_google_maps') <div class="invalid-feedback" style="display:block">{{ $message }}</div> @enderror
                        </div>

                        {{-- Preview tombol Maps --}}
                        <div id="maps-preview" style="display:none;margin-top:8px">
                            <a id="maps-preview-link" href="#" target="_blank" rel="noopener noreferrer"
                               style="display:inline-flex;align-items:center;gap:6px;padding:6px 12px;background:rgba(37,99,235,.08);color:#2563eb;border-radius:var(--r-sm);font-size:12.5px;font-weight:600;text-decoration:none;border:1px solid rgba(37,99,235,.2)">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
                                Preview: Lihat di Google Maps
                            </a>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                        Tambah Alamat Baru
                    </button>
                </form>
            </div>

        </div>{{-- /kolom kanan --}}
        @endunless

    </div>{{-- /outer container --}}
</div>
@endsection

@push('scripts')
<script>
function updateMapsPreview() {
    const lat = document.getElementById('input-lat')?.value;
    const lng = document.getElementById('input-lng')?.value;
    const preview = document.getElementById('maps-preview');
    const link    = document.getElementById('maps-preview-link');

    if (lat && lng && !isNaN(lat) && !isNaN(lng)) {
        const url = 'https://www.google.com/maps?q=' + parseFloat(lat) + ',' + parseFloat(lng);
        link.href = url;
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
}
</script>
@endpush
