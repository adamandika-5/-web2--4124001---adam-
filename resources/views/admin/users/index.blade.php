@extends('layouts.admin')
@section('title', 'Manajemen User')
@section('page_title', 'Manajemen User')
@section('breadcrumb', 'Sistem › Manajemen User')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px">
    {{-- Filter --}}
    <form method="GET" style="display:flex;gap:8px;align-items:center">
        <input class="form-inp" type="text" name="q" value="{{ request('q') }}"
               placeholder="Cari nama atau email..."
               style="width:240px;padding:8px 12px;font-size:13px">
        <select class="form-inp" name="role" style="width:120px;font-size:13px;padding:8px">
            <option value="">Semua Role</option>
            <option value="user"  {{ request('role')==='user'  ?'selected':'' }}>User</option>
            <option value="admin" {{ request('role')==='admin' ?'selected':'' }}>Admin</option>
        </select>
        <select class="form-inp" name="status" style="width:130px;font-size:13px;padding:8px">
            <option value="">Semua Status</option>
            <option value="aktif"    {{ request('status')==='aktif'    ?'selected':'' }}>Aktif</option>
            <option value="nonaktif" {{ request('status')==='nonaktif' ?'selected':'' }}>Nonaktif</option>
        </select>
        <button class="btn btn-secondary" style="font-size:13px;padding:8px 14px">Filter</button>
        @if(request()->hasAny(['q','role','status']))
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="font-size:13px;padding:8px 14px">Reset</a>
        @endif
    </form>

    <a href="{{ route('admin.users.create') }}" class="btn btn-primary" style="font-size:13px;padding:8px 18px">
        + Tambah User
    </a>
</div>

{{-- Stats mini --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px;max-width:500px">
    @foreach([['👤','Pelanggan',$totalUser,'var(--soil)'],['⚙️','Admin/Staff',$totalAdmin,'var(--terracotta)'],['🚫','Nonaktif',$nonaktif,'#dc2626']] as [$ikon,$label,$val,$warna])
    <div style="background:#fff;border-radius:var(--r-md);padding:14px 18px;border:1px solid rgba(176,139,110,.08);box-shadow:var(--sh-sm)">
        <div style="font-size:22px;font-weight:700;color:{{ $warna }};font-family:var(--fd)">{{ $val }}</div>
        <div style="font-size:12px;color:var(--clay)">{{ $ikon }} {{ $label }}</div>
    </div>
    @endforeach
</div>

{{-- Tabel --}}
<div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07);overflow:hidden">
    <table style="width:100%;border-collapse:collapse;font-size:13.5px">
        <thead>
            <tr style="background:var(--oat)">
                <th style="padding:12px 16px;text-align:left;color:var(--clay);font-weight:600">User</th>
                <th style="padding:12px 16px;text-align:left;color:var(--clay);font-weight:600">Email</th>
                <th style="padding:12px 16px;text-align:center;color:var(--clay);font-weight:600">Role</th>
                <th style="padding:12px 16px;text-align:center;color:var(--clay);font-weight:600">Status</th>
                <th style="padding:12px 16px;text-align:left;color:var(--clay);font-weight:600">Daftar</th>
                <th style="padding:12px 16px;text-align:center;color:var(--clay);font-weight:600">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr style="border-top:1px solid rgba(176,139,110,.06)">
                <td style="padding:12px 16px">
                    <div style="display:flex;align-items:center;gap:10px">
                        <div class="adm-avatar" style="width:32px;height:32px;font-size:11px;flex-shrink:0">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div style="font-weight:600;color:var(--soil)">{{ $user->name }}</div>
                    </div>
                </td>
                <td style="padding:12px 16px;color:var(--clay)">{{ $user->email }}</td>
                <td style="padding:12px 16px;text-align:center">
                    <span style="padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:700;
                        background:{{ $user->role === 'admin' ? 'rgba(198,107,61,.12)' : 'rgba(37,99,235,.08)' }};
                        color:{{ $user->role === 'admin' ? 'var(--terracotta)' : '#2563eb' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td style="padding:12px 16px;text-align:center">
                    <span style="padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:700;
                        background:{{ $user->aktif ? 'rgba(22,163,74,.1)' : 'rgba(220,38,38,.1)' }};
                        color:{{ $user->aktif ? '#16a34a' : '#dc2626' }}">
                        {{ $user->aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td style="padding:12px 16px;color:var(--clay);font-size:12px">
                    {{ $user->created_at?->format('d M Y') }}
                </td>
                <td style="padding:12px 16px;text-align:center">
                    <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           style="font-size:12px;color:var(--terracotta);font-weight:600;text-decoration:none">Edit</a>

                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.toggleAktif', $user) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    style="font-size:12px;color:#2563eb;font-weight:600;background:none;border:none;cursor:pointer;padding:0">
                                {{ $user->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.users.resetPassword', $user) }}" method="POST"
                              onsubmit="return confirm('Reset password {{ $user->name }}?')">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    style="font-size:12px;color:#7c3aed;font-weight:600;background:none;border:none;cursor:pointer;padding:0">
                                Reset PW
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="padding:40px;text-align:center;color:var(--clay)">
                    Tidak ada data user yang cocok.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="padding:14px 20px">
        {{ $users->links() }}
    </div>
</div>

@endsection
