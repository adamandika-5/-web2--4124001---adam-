@extends('layouts.admin')
@section('title', 'Manajemen Supplier')
@section('page_title', 'Manajemen Supplier')
@section('breadcrumb', 'Layanan › Supplier')

@section('content')

<div class="adm-filter-bar">
    {{-- Filter/Search --}}
    <form method="GET">
        <input class="form-inp" type="text" name="q" value="{{ request('q') }}"
               placeholder="Cari nama, email, kota..."
               style="padding:8px 12px;font-size:13px">
        <select class="form-inp" name="status" style="font-size:13px;padding:8px">
            <option value="">Semua Status</option>
            <option value="aktif"    {{ request('status')==='aktif'    ?'selected':'' }}>Aktif</option>
            <option value="nonaktif" {{ request('status')==='nonaktif' ?'selected':'' }}>Nonaktif</option>
        </select>
        <button class="btn btn-secondary" style="font-size:13px;padding:8px 14px">Filter</button>
        @if(request()->hasAny(['q','status']))
            <a href="{{ route('admin.supplier.index') }}" class="btn btn-secondary" style="font-size:13px;padding:8px 14px">Reset</a>
        @endif
    </form>

    <a href="{{ route('admin.supplier.create') }}" class="btn btn-primary" style="font-size:13px;padding:8px 18px">
        + Tambah Supplier
    </a>
</div>

{{-- Stats mini --}}
<div class="adm-supplier-stats">
    <div style="background:#fff;border-radius:var(--r-md);padding:14px 18px;border:1px solid rgba(176,139,110,.08);box-shadow:var(--sh-sm)">
        <div style="font-size:22px;font-weight:700;color:var(--soil);font-family:var(--fd)">{{ $total }}</div>
        <div style="font-size:12px;color:var(--clay)">Total Supplier</div>
    </div>
    <div style="background:#fff;border-radius:var(--r-md);padding:14px 18px;border:1px solid rgba(176,139,110,.08);box-shadow:var(--sh-sm)">
        <div style="font-size:22px;font-weight:700;color:var(--terracotta);font-family:var(--fd)">{{ $aktif }}</div>
        <div style="font-size:12px;color:var(--clay)">Supplier Aktif</div>
    </div>
</div>

{{-- Tabel --}}
<div class="adm-table-card">
    <div class="table-scroll-wrap">
    <table style="width:100%;border-collapse:collapse;font-size:13.5px">
        <thead>
            <tr style="background:var(--oat)">
                <th style="padding:12px 16px;text-align:left;color:var(--clay);font-weight:600">Nama Supplier</th>
                <th style="padding:12px 16px;text-align:left;color:var(--clay);font-weight:600">Kontak</th>
                <th style="padding:12px 16px;text-align:left;color:var(--clay);font-weight:600">Kota</th>
                <th style="padding:12px 16px;text-align:center;color:var(--clay);font-weight:600">Status</th>
                <th style="padding:12px 16px;text-align:center;color:var(--clay);font-weight:600">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $supplier)
            <tr style="border-top:1px solid rgba(176,139,110,.06)">
                <td style="padding:12px 16px">
                    <div style="font-weight:600;color:var(--soil)">{{ $supplier->nama }}</div>
                    @if($supplier->email)
                    <div style="font-size:12px;color:var(--clay)">{{ $supplier->email }}</div>
                    @endif
                </td>
                <td style="padding:12px 16px;color:var(--clay)">
                    {{ $supplier->kontak ?? '-' }}
                    @if($supplier->telepon)
                    <div style="font-size:12px">{{ $supplier->telepon }}</div>
                    @endif
                </td>
                <td style="padding:12px 16px;color:var(--clay)">{{ $supplier->kota ?? '-' }}</td>
                <td style="padding:12px 16px;text-align:center">
                    <span style="padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:700;
                        background:{{ $supplier->aktif ? 'rgba(22,163,74,.1)' : 'rgba(220,38,38,.1)' }};
                        color:{{ $supplier->aktif ? '#16a34a' : '#dc2626' }}">
                        {{ $supplier->aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td style="padding:12px 16px;text-align:center">
                    <div style="display:flex;gap:8px;justify-content:center;align-items:center">
                        {{-- Toggle Aktif/Nonaktif --}}
                        <form action="{{ route('admin.supplier.toggle', $supplier) }}" method="POST"
                              onsubmit="return confirm('{{ $supplier->aktif ? 'Nonaktifkan' : 'Aktifkan' }} supplier {{ $supplier->nama }}?')">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    style="font-size:12.5px;font-weight:600;background:none;border:none;cursor:pointer;padding:0;
                                           color:{{ $supplier->aktif ? '#a05a2c' : '#16a34a' }}">
                                {{ $supplier->aktif ? '⏸ Nonaktifkan' : '▶ Aktifkan' }}
                            </button>
                        </form>
                        <span style="color:var(--sand)">·</span>
                        <a href="{{ route('admin.supplier.edit', $supplier) }}"
                           style="font-size:12.5px;color:var(--terracotta);font-weight:600;text-decoration:none">Edit</a>
                        <span style="color:var(--sand)">·</span>
                        <form action="{{ route('admin.supplier.destroy', $supplier) }}" method="POST"
                              onsubmit="return confirm('Hapus supplier {{ $supplier->nama }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="font-size:12.5px;color:#dc2626;font-weight:600;background:none;border:none;cursor:pointer;padding:0">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:40px;text-align:center;color:var(--clay)">
                    Belum ada data supplier.
                    <a href="{{ route('admin.supplier.create') }}" style="color:var(--terracotta);font-weight:600;text-decoration:none">+ Tambah sekarang</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>{{-- /table-scroll-wrap --}}

    <div style="padding:14px 20px">
        {{ $suppliers->links() }}
    </div>
</div>

@endsection
