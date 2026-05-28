@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div style="max-width:1280px;margin:0 auto;padding:32px 48px 64px">

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

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:28px;align-items:start">

        {{-- KOLOM KIRI: Informasi Profil & Ubah Sandi --}}
        <div style="display:flex;flex-direction:column;gap:24px">

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

        </div>

        {{-- KOLOM KANAN: Daftar Alamat & Form Tambah Alamat --}}
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

                        {{-- Hapus Alamat (jika bukan utama) --}}
                        @if(!$alamat->is_utama)
                            <form action="{{ route('profil.alamat.delete', $alamat->id) }}" method="POST"
                                  style="margin-top:10px"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        style="background:none;border:none;color:#c03030;font-size:12.5px;font-weight:700;cursor:pointer;padding:0;font-family:var(--fb)">
                                    🗑️ Hapus Alamat
                                </button>
                            </form>
                        @endif
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

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
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

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                        <div class="form-grp">
                            <label class="form-lbl">Kota / Kabupaten *</label>
                            <input type="text" name="kota" class="form-inp" required placeholder="cth: Pasuruan">
                        </div>
                        <div class="form-grp">
                            <label class="form-lbl">Provinsi *</label>
                            <input type="text" name="provinsi" class="form-inp" required placeholder="cth: Jawa Timur">
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px">
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

                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                        Tambah Alamat Baru
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
