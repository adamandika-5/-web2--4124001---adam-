@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div style="max-width:1280px;margin:0 auto;padding:32px 48px 64px">
    <div style="margin-bottom:28px">
        <div class="section-label">Akun Saya</div>
        <h1 class="section-title" style="font-size:clamp(26px,3vw,38px)">Profil Saya</h1>
    </div>

    {{-- Error/Success Alerts --}}
    @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:20px; background: #fee2e2; border: 1.5px solid #fca5a5; padding: 12px 18px; border-radius: var(--r-md); color: #991b1b; font-size: 13.5px;">
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
            <div style="background:#fff;border-radius:var(--r-lg);padding:24px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08)">
                <div style="font-family:var(--fd);font-size:18px;font-weight:700;color:var(--soil);margin-bottom:16px;padding-bottom:10px;border-bottom:1.5px solid var(--sand);display:flex;align-items:center;gap:8px">
                    👤 Edit Profil
                </div>
                
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
                            <label style="font-size:12px;font-weight:700;color:var(--clay);display:block;margin-bottom:4px">Foto Profil (Avatar)</label>
                            <input type="file" name="avatar" style="font-size:13px;color:var(--clay)">
                        </div>
                    </div>

                    <div style="margin-bottom:14px">
                        <label style="font-size:12px;font-weight:700;color:var(--clay);display:block;margin-bottom:6px">Nama Lengkap *</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                               style="width:100%;box-sizing:border-box;padding:10px 12px;border:1.5px solid rgba(176,139,110,.2);border-radius:var(--r-sm);font-family:var(--fb);font-size:13.5px;color:var(--soil);outline:none">
                    </div>

                    <div style="margin-bottom:14px">
                        <label style="font-size:12px;font-weight:700;color:var(--clay);display:block;margin-bottom:6px">Alamat Email *</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                               style="width:100%;box-sizing:border-box;padding:10px 12px;border:1.5px solid rgba(176,139,110,.2);border-radius:var(--r-sm);font-family:var(--fb);font-size:13.5px;color:var(--soil);outline:none">
                    </div>

                    <div style="margin-bottom:14px">
                        <label style="font-size:12px;font-weight:700;color:var(--clay);display:block;margin-bottom:6px">Nomor Telepon</label>
                        <input type="text" name="telepon" value="{{ old('telepon', auth()->user()->telepon) }}"
                               style="width:100%;box-sizing:border-box;padding:10px 12px;border:1.5px solid rgba(176,139,110,.2);border-radius:var(--r-sm);font-family:var(--fb);font-size:13.5px;color:var(--soil);outline:none">
                    </div>

                    <div style="margin-bottom:20px">
                        <label style="font-size:12px;font-weight:700;color:var(--clay);display:block;margin-bottom:6px">Role Akun</label>
                        <input type="text" value="{{ ucfirst(auth()->user()->role) }}" disabled
                               style="width:100%;box-sizing:border-box;padding:10px 12px;border:1.5px solid rgba(176,139,110,.1);border-radius:var(--r-sm);font-family:var(--fb);font-size:13.5px;color:var(--clay);background:var(--oat);outline:none">
                    </div>

                    <button type="submit" class="btn btn-primary" style="font-size:13.5px;padding:10px 20px;width:100%;justify-content:center">
                        Simpan Perubahan Profil
                    </button>
                </form>
            </div>

            {{-- Card Ubah Password --}}
            <div style="background:#fff;border-radius:var(--r-lg);padding:24px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08)">
                <div style="font-family:var(--fd);font-size:18px;font-weight:700;color:var(--soil);margin-bottom:16px;padding-bottom:10px;border-bottom:1.5px solid var(--sand);display:flex;align-items:center;gap:8px">
                    🔑 Ubah Kata Sandi
                </div>

                <form action="{{ route('profil.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="margin-bottom:14px">
                        <label style="font-size:12px;font-weight:700;color:var(--clay);display:block;margin-bottom:6px">Kata Sandi Sekarang *</label>
                        <input type="password" name="current_password" required
                               style="width:100%;box-sizing:border-box;padding:10px 12px;border:1.5px solid rgba(176,139,110,.2);border-radius:var(--r-sm);font-family:var(--fb);font-size:13.5px;outline:none">
                    </div>

                    <div style="margin-bottom:14px">
                        <label style="font-size:12px;font-weight:700;color:var(--clay);display:block;margin-bottom:6px">Kata Sandi Baru * (Minimal 8 Karakter)</label>
                        <input type="password" name="password" required
                               style="width:100%;box-sizing:border-box;padding:10px 12px;border:1.5px solid rgba(176,139,110,.2);border-radius:var(--r-sm);font-family:var(--fb);font-size:13.5px;outline:none">
                    </div>

                    <div style="margin-bottom:20px">
                        <label style="font-size:12px;font-weight:700;color:var(--clay);display:block;margin-bottom:6px">Konfirmasi Kata Sandi Baru *</label>
                        <input type="password" name="password_confirmation" required
                               style="width:100%;box-sizing:border-box;padding:10px 12px;border:1.5px solid rgba(176,139,110,.2);border-radius:var(--r-sm);font-family:var(--fb);font-size:13.5px;outline:none">
                    </div>

                    <button type="submit" class="btn btn-secondary" style="font-size:13.5px;padding:10px 20px;width:100%;justify-content:center">
                        Perbarui Kata Sandi
                    </button>
                </form>
            </div>

        </div>

        {{-- KOLOM KANAN: Daftar Alamat & Form Tambah Alamat --}}
        <div style="display:flex;flex-direction:column;gap:24px">
            
            {{-- Daftar Alamat --}}
            <div style="background:#fff;border-radius:var(--r-lg);padding:24px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08)">
                <div style="font-family:var(--fd);font-size:18px;font-weight:700;color:var(--soil);margin-bottom:16px;padding-bottom:10px;border-bottom:1.5px solid var(--sand)">
                    📍 Alamat Pengiriman
                </div>

                @php
                    $alamatCollection = $alamats ?? collect();
                @endphp

                @forelse($alamatCollection as $alamat)
                    <div style="padding:16px;background:var(--oat);border-radius:var(--r-md);border:1.5px solid rgba(176,139,110,.12);margin-bottom:12px;position:relative">
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
                            {{ $alamat->kota }}, {{ $alamat->provinsi }} @if($alamat->kode_pos) · {{ $alamat->kode_pos }} @endif
                        </div>

                        {{-- Delete Alamat (jika bukan utama) --}}
                        @if(!$alamat->is_utama)
                            <form action="{{ route('profil.alamat.delete', $alamat->id) }}" method="POST" style="margin-top:10px" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none;border:none;color:#c03030;font-size:12.5px;font-weight:700;cursor:pointer;padding:0">
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
            <div style="background:#fff;border-radius:var(--r-lg);padding:24px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08)">
                <div style="font-family:var(--fd);font-size:18px;font-weight:700;color:var(--soil);margin-bottom:16px;padding-bottom:10px;border-bottom:1.5px solid var(--sand)">
                    ➕ Tambah Alamat Baru
                </div>

                <form action="{{ route('profil.alamat.store') }}" method="POST">
                    @csrf

                    <div style="margin-bottom:12px">
                        <label style="font-size:12px;font-weight:700;color:var(--clay);display:block;margin-bottom:6px">Label Alamat * (cth: Rumah, Kantor)</label>
                        <input type="text" name="label" required placeholder="Rumah / Kantor"
                               style="width:100%;box-sizing:border-box;padding:10px 12px;border:1.5px solid rgba(176,139,110,.2);border-radius:var(--r-sm);font-family:var(--fb);font-size:13px;outline:none">
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
                        <div>
                            <label style="font-size:12px;font-weight:700;color:var(--clay);display:block;margin-bottom:6px">Nama Penerima *</label>
                            <input type="text" name="penerima" required placeholder="Nama penerima"
                                   style="width:100%;box-sizing:border-box;padding:10px 12px;border:1.5px solid rgba(176,139,110,.2);border-radius:var(--r-sm);font-family:var(--fb);font-size:13px;outline:none">
                        </div>
                        <div>
                            <label style="font-size:12px;font-weight:700;color:var(--clay);display:block;margin-bottom:6px">No. Telepon Penerima *</label>
                            <input type="text" name="telepon" required placeholder="08xxx"
                                   style="width:100%;box-sizing:border-box;padding:10px 12px;border:1.5px solid rgba(176,139,110,.2);border-radius:var(--r-sm);font-family:var(--fb);font-size:13px;outline:none">
                        </div>
                    </div>

                    <div style="margin-bottom:12px">
                        <label style="font-size:12px;font-weight:700;color:var(--clay);display:block;margin-bottom:6px">Alamat Lengkap * (Jalan, No Rumah, RT/RW)</label>
                        <textarea name="alamat_lengkap" required rows="3" placeholder="Masukkan alamat lengkap..."
                                  style="width:100%;box-sizing:border-box;padding:10px 12px;border:1.5px solid rgba(176,139,110,.2);border-radius:var(--r-sm);font-family:var(--fb);font-size:13px;outline:none;resize:vertical"></textarea>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
                        <div>
                            <label style="font-size:12px;font-weight:700;color:var(--clay);display:block;margin-bottom:6px">Kota / Kabupaten *</label>
                            <input type="text" name="kota" required placeholder="cth: Pasuruan"
                                   style="width:100%;box-sizing:border-box;padding:10px 12px;border:1.5px solid rgba(176,139,110,.2);border-radius:var(--r-sm);font-family:var(--fb);font-size:13px;outline:none">
                        </div>
                        <div>
                            <label style="font-size:12px;font-weight:700;color:var(--clay);display:block;margin-bottom:6px">Provinsi *</label>
                            <input type="text" name="provinsi" required placeholder="cth: Jawa Timur"
                                   style="width:100%;box-sizing:border-box;padding:10px 12px;border:1.5px solid rgba(176,139,110,.2);border-radius:var(--r-sm);font-family:var(--fb);font-size:13px;outline:none">
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px">
                        <div>
                            <label style="font-size:12px;font-weight:700;color:var(--clay);display:block;margin-bottom:6px">Kode Pos</label>
                            <input type="text" name="kode_pos" placeholder="67xxx"
                                   style="width:100%;box-sizing:border-box;padding:10px 12px;border:1.5px solid rgba(176,139,110,.2);border-radius:var(--r-sm);font-family:var(--fb);font-size:13px;outline:none">
                        </div>
                        <div style="display:flex;align-items:center;margin-top:20px">
                            <label style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--soil);cursor:pointer">
                                <input type="checkbox" name="is_utama" value="1">
                                Jadikan Alamat Utama
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="font-size:13.5px;padding:10px 20px;width:100%;justify-content:center">
                        Tambah Alamat Baru
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>
@endsection
