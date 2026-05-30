@extends('layouts.admin')
@section('title', 'Detail User — ' . $user->name)
@section('page_title', 'Detail User')
@section('breadcrumb', 'Sistem › User › ' . $user->name)

@section('content')

<div style="display:grid;grid-template-columns:1fr 2fr;gap:20px">

    {{-- Kartu Profil --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        <div style="background:#fff;border-radius:var(--r-lg);padding:28px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07);text-align:center">
            <div style="width:72px;height:72px;background:var(--terracotta);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:700;color:#fff;margin:0 auto 16px;overflow:hidden">
                @if($user->avatar)
                    <img src="{{ asset('storage/'.$user->avatar) }}" style="width:100%;height:100%;object-fit:cover">
                @else
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                @endif
            </div>
            <div style="font-family:var(--fd);font-size:18px;font-weight:500;color:var(--soil)">{{ $user->name }}</div>
            <div style="font-size:13px;color:var(--clay);margin-top:4px">{{ $user->email }}</div>
            @if($user->telepon)
                <div style="font-size:13px;color:var(--clay)">📞 {{ $user->telepon }}</div>
            @endif
            <div style="margin-top:12px;display:flex;gap:8px;justify-content:center;flex-wrap:wrap">
                @php $roleStyle = match($user->role) {
                    'admin' => 'background:rgba(198,107,61,.12);color:var(--terra-dark)',
                    default => 'background:rgba(96,108,56,.1);color:var(--moss)',
                }; @endphp
                <span class="badge" style="{{ $roleStyle }};font-size:12px">{{ ucfirst($user->role) }}</span>
                <span class="status-pill {{ $user->aktif ? 's-lunas' : 's-batal' }}">
                    {{ $user->aktif ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
            <div style="font-size:12px;color:var(--clay);margin-top:12px">
                Bergabung {{ $user->created_at->isoFormat('D MMMM Y') }}
            </div>
        </div>

        {{-- Aksi --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:18px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
            <div style="display:flex;flex-direction:column;gap:8px">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-secondary" style="width:100%;justify-content:center;font-size:13px">
                    ✏️ Edit Data User
                </a>
                <form action="{{ route('admin.users.toggleAktif', $user->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-secondary" style="width:100%;justify-content:center;font-size:13px;{{ $user->aktif ? 'color:#c03030;border-color:rgba(192,48,48,.3)' : '' }}">
                        {{ $user->aktif ? '🚫 Nonaktifkan' : '✅ Aktifkan' }} Akun
                    </button>
                </form>
                <form action="{{ route('admin.users.resetPassword', $user->id) }}" method="POST"
                      onsubmit="return confirm('Reset password {{ addslashes($user->name) }}?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-secondary" style="width:100%;justify-content:center;font-size:13px">
                        🔑 Reset Password
                    </button>
                </form>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="width:100%;justify-content:center;font-size:13px">
                    ← Kembali ke Daftar
                </a>
            </div>
        </div>

        {{-- Statistik --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:18px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
            <div style="font-size:13px;font-weight:700;color:var(--soil);margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--sand)">Statistik</div>
            <div style="display:flex;flex-direction:column;gap:10px">
                <div style="display:flex;justify-content:space-between;font-size:13px">
                    <span style="color:var(--clay)">Total Pesanan</span>
                    <span style="font-weight:700;color:var(--soil)">{{ $user->pesanan->count() }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px">
                    <span style="color:var(--clay)">Total Belanja</span>
                    <span style="font-weight:700;color:var(--terracotta)">Rp {{ number_format($user->pesanan->where('status','selesai')->sum('total'), 0, ',', '.') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px">
                    <span style="color:var(--clay)">Riwayat Sewa</span>
                    <span style="font-weight:700;color:var(--soil)">{{ $user->bookings->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Kanan --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- Riwayat Pesanan --}}
        <div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);overflow:hidden;border:1px solid rgba(176,139,110,.07)">
            <div style="padding:14px 20px;border-bottom:1px solid rgba(176,139,110,.09);font-family:var(--fd);font-size:15.5px;font-weight:500;color:var(--soil)">
                Riwayat Pesanan
            </div>
            <table class="data-tbl">
                <thead>
                    <tr><th>No. Pesanan</th><th>Total</th><th>Status</th><th>Tanggal</th></tr>
                </thead>
                <tbody>
                    @forelse($user->pesanan->take(5) as $p)
                    <tr>
                        <td><a href="{{ route('admin.pesanan.show', $p->id) }}" style="color:var(--terracotta);font-weight:700;text-decoration:none">{{ $p->nomor_pesanan }}</a></td>
                        <td style="font-weight:600">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                        <td>
                            @php $sc = match($p->status) { 'selesai'=>'s-lunas','diproses'=>'s-proses','pending'=>'s-pending','batal'=>'s-batal',default=>'s-pending' }; @endphp
                            <span class="status-pill {{ $sc }}">{{ ucfirst($p->status) }}</span>
                        </td>
                        <td style="font-size:12px;color:var(--clay)">{{ $p->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;padding:20px;color:var(--clay)">Belum ada pesanan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Activity Log --}}
        <div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);overflow:hidden;border:1px solid rgba(176,139,110,.07)">
            <div style="padding:14px 20px;border-bottom:1px solid rgba(176,139,110,.09);font-family:var(--fd);font-size:15.5px;font-weight:500;color:var(--soil)">
                Aktivitas Terakhir
            </div>
            <div style="padding:8px 0">
                @forelse($user->activityLogs as $log)
                <div style="display:flex;align-items:flex-start;gap:12px;padding:11px 20px;border-bottom:1px solid rgba(176,139,110,.05)">
                    <div style="width:30px;height:30px;background:rgba(198,107,61,.08);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0">
                        {{ $log->ikon ?? '📝' }}
                    </div>
                    <div style="flex:1">
                        <div style="font-size:13px;color:var(--soil)">{{ $log->deskripsi }}</div>
                        <div style="font-size:11.5px;color:var(--clay);margin-top:2px">{{ $log->created_at->diffForHumans() }} · IP: {{ $log->ip_address }}</div>
                    </div>
                </div>
                @empty
                <div style="text-align:center;padding:24px;color:var(--clay);font-size:13px">Belum ada aktivitas</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection