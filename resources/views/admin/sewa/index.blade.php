@extends('layouts.admin')
@section('title', 'Sewa Alat')
@section('page_title', 'Manajemen Sewa Alat')
@section('breadcrumb', 'Layanan › Sewa Alat')

@section('content')

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:16px;margin-bottom:24px">
    @foreach([
        ['🔧', 'Total Alat',     $stats['total'],         'var(--soil)',       null],
        ['✅', 'Tersedia',        $stats['tersedia'],       'var(--terracotta)', null],
        ['⏳', 'Sedang Disewa',  $stats['sedang_disewa'],  '#2563eb',           null],
        ['🔔', 'Menunggu Konfirmasi', $stats['pending'],   '#b45309',           route('admin.sewa.booking', ['status'=>'pending'])],
        ['⚠️', 'Terlambat',      $stats['terlambat'],      '#dc2626',           null],
    ] as [$ikon, $label, $val, $warna, $href])
    @if($href)
    <a href="{{ $href }}" style="text-decoration:none">
    @endif
    <div style="background:#fff;border-radius:var(--r-lg);padding:20px 22px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07){{ $href ? ';transition:box-shadow .15s' : '' }}"
         {{ $href ? "onmouseover=\"this.style.boxShadow='var(--sh-md)'\" onmouseout=\"this.style.boxShadow='var(--sh-sm)'\"" : '' }}>
        <div style="font-size:22px;margin-bottom:6px">{{ $ikon }}</div>
        <div style="font-size:26px;font-weight:700;color:{{ $warna }};font-family:var(--fd)">{{ $val }}</div>
        <div style="font-size:12.5px;color:var(--clay);margin-top:2px">{{ $label }}</div>
    </div>
    @if($href)
    </a>
    @endif
    @endforeach
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

    {{-- Daftar Alat --}}
    <div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
        <div style="padding:20px 24px;border-bottom:1px solid rgba(176,139,110,.07);display:flex;align-items:center;justify-content:space-between">
            <div style="font-family:var(--fd);font-size:16px;font-weight:500;color:var(--soil)">Daftar Alat Sewa</div>
            <button onclick="document.getElementById('modal-tambah-alat').style.display='flex'"
                    class="btn btn-primary" style="font-size:13px;padding:7px 16px">
                + Tambah Alat
            </button>
        </div>

        <div style="overflow-x:auto">
            <table style="width:100%;border-collapse:collapse;font-size:13px">
                <thead>
                    <tr style="background:var(--oat)">
                        <th style="padding:10px 14px;text-align:left;color:var(--clay);font-weight:600">Nama Alat</th>
                        <th style="padding:10px 14px;text-align:center;color:var(--clay);font-weight:600">Tersedia</th>
                        <th style="padding:10px 14px;text-align:right;color:var(--clay);font-weight:600">Tarif/Hari</th>
                        <th style="padding:10px 14px;text-align:center;color:var(--clay);font-weight:600">Status</th>
                        <th style="padding:10px 14px;text-align:center;color:var(--clay);font-weight:600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alat as $item)
                    <tr style="border-top:1px solid rgba(176,139,110,.06)">
                        <td style="padding:10px 14px">
                            <div style="font-weight:600;color:var(--soil)">{{ $item->nama }}</div>
                            @if($item->kategori_alat)
                            <div style="font-size:11.5px;color:var(--clay)">{{ $item->kategori_alat }}</div>
                            @endif
                        </td>
                        <td style="padding:10px 14px;text-align:center">
                            <span style="font-weight:700;color:{{ $item->tersedia > 0 ? 'var(--terracotta)' : '#dc2626' }}">
                                {{ $item->tersedia }}/{{ $item->jumlah_unit }}
                            </span>
                        </td>
                        <td style="padding:10px 14px;text-align:right;font-weight:600;color:var(--soil)">
                            Rp {{ number_format($item->tarif_harian, 0, ',', '.') }}
                        </td>
                        <td style="padding:10px 14px;text-align:center">
                            <span style="padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:700;background:{{ $item->aktif ? 'rgba(22,163,74,.1)' : 'rgba(220,38,38,.1)' }};color:{{ $item->aktif ? '#16a34a' : '#dc2626' }}">
                                {{ $item->aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td style="padding:10px 14px;text-align:center">
                            <a href="{{ route('admin.sewa.edit', $item) }}"
                               style="font-size:12.5px;color:var(--terracotta);font-weight:600;text-decoration:none">
                                Edit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding:32px;text-align:center;color:var(--clay)">
                            Belum ada data alat sewa.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding:14px 20px">
            {{ $alat->links() }}
        </div>
    </div>

    {{-- Booking Aktif --}}
    <div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
        <div style="padding:20px 24px;border-bottom:1px solid rgba(176,139,110,.07);display:flex;align-items:center;justify-content:space-between">
            <div style="font-family:var(--fd);font-size:16px;font-weight:500;color:var(--soil)">Booking Terkini (Perlu Tindakan)</div>
            <a href="{{ route('admin.sewa.booking') }}"
               style="font-size:13px;color:var(--terracotta);font-weight:600;text-decoration:none">
                Lihat Semua &rarr;
            </a>
        </div>

        <div style="padding:4px 0">
            @forelse($booking as $b)
            <div style="padding:14px 20px;border-bottom:1px solid rgba(176,139,110,.06)">
                <div style="display:flex;justify-content:space-between;align-items:start">
                    <div>
                        <div style="font-size:13px;font-weight:600;color:var(--soil)">{{ $b->nomor_booking }}</div>
                        <div style="font-size:12px;color:var(--clay);margin-top:2px">
                            {{ $b->user?->name }} — {{ $b->alat?->nama }}
                        </div>
                        <div style="font-size:11.5px;color:var(--clay);margin-top:3px">
                            {{ $b->tanggal_mulai?->format('d M') }} – {{ $b->tanggal_selesai?->format('d M Y') }}
                        </div>
                    </div>
                    @php
                        $isLate = $b->status === 'aktif' && $b->tanggal_selesai && $b->tanggal_selesai->isPast();
                    @endphp
                    @if($b->status === 'pending')
                    <span style="padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700;background:rgba(234,179,8,.12);color:#b45309">
                        ⏳ Pending
                    </span>
                    @elseif($isLate)
                    <span style="padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700;background:rgba(220,38,38,.1);color:#dc2626">
                        ⚠ Terlambat
                    </span>
                    @else
                    <span style="padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700;background:rgba(22,163,74,.1);color:#16a34a">
                        ✅ Aktif
                    </span>
                    @endif
                </div>
            </div>
            @empty
            <div style="padding:32px;text-align:center;color:var(--clay);font-size:13px">
                Tidak ada booking aktif saat ini.
            </div>
            @endforelse
        </div>
    </div>

</div>

{{-- Modal Tambah Alat --}}
<div id="modal-tambah-alat"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:500;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:var(--r-lg);padding:32px;width:100%;max-width:520px;max-height:90vh;overflow-y:auto;margin:20px">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px">
            <div style="font-family:var(--fd);font-size:18px;font-weight:500;color:var(--soil)">🔧 Tambah Alat Sewa</div>
            <button onclick="document.getElementById('modal-tambah-alat').style.display='none'"
                    style="background:none;border:none;font-size:20px;cursor:pointer;color:var(--clay)">✕</button>
        </div>

        <form action="{{ route('admin.sewa.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div class="form-grp" style="grid-column:1/-1">
                    <label class="form-lbl">Nama Alat *</label>
                    <input class="form-inp" type="text" name="nama" required placeholder="cth: Molen Beton 350L">
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Kategori Alat</label>
                    <input class="form-inp" type="text" name="kategori_alat" placeholder="cth: Pengecoran">
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Kondisi</label>
                    <select class="form-inp" name="kondisi">
                        <option value="baik">Baik</option>
                        <option value="cukup">Cukup</option>
                        <option value="perbaikan">Perlu Perbaikan</option>
                    </select>
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Tarif Harian (Rp) *</label>
                    <input class="form-inp" type="number" name="tarif_harian" required min="0" placeholder="150000">
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Tarif Mingguan (Rp)</label>
                    <input class="form-inp" type="number" name="tarif_mingguan" min="0" placeholder="900000">
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Deposit (Rp)</label>
                    <input class="form-inp" type="number" name="deposit" min="0" placeholder="500000">
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Denda/Hari Terlambat (Rp)</label>
                    <input class="form-inp" type="number" name="denda_per_hari" min="0" placeholder="50000">
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Jumlah Unit *</label>
                    <input class="form-inp" type="number" name="jumlah_unit" required min="1" value="1">
                </div>
                <div class="form-grp" style="grid-column:1/-1">
                    <label class="form-lbl">Deskripsi</label>
                    <textarea class="form-inp" name="deskripsi" rows="2" placeholder="Deskripsi singkat alat..."></textarea>
                </div>
                <div class="form-grp" style="grid-column:1/-1">
                    <label class="form-lbl">Foto Alat</label>
                    <input type="file" name="gambar" accept="image/*" class="form-inp" style="padding:7px;font-size:13px">
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">Simpan</button>
                <button type="button"
                        onclick="document.getElementById('modal-tambah-alat').style.display='none'"
                        class="btn btn-secondary">Batal</button>
            </div>
        </form>
    </div>
</div>

@endsection
