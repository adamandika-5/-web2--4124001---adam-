@extends('layouts.admin')
@section('title','Semua Booking Alat')
@section('page_title','Daftar Booking Alat')
@section('breadcrumb','Layanan › Sewa Alat › Semua Booking')

@section('content')

{{-- Filter --}}
<div style="display:flex;gap:10px;align-items:center;margin-bottom:16px;flex-wrap:wrap">
    <form method="GET" action="{{ route('admin.sewa.booking') }}" style="display:flex;gap:10px;flex:1;flex-wrap:wrap">
        <div style="position:relative;flex:1;min-width:200px">
            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--clay);pointer-events:none;width:15px;height:15px"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/>
            </svg>
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="No. booking, nama pelanggan..."
                   style="width:100%;padding:8px 14px 8px 36px;border:1.5px solid var(--sand);border-radius:var(--r-md);background:#fff;font-family:var(--fb);font-size:13px;color:var(--soil);outline:none"
                   onfocus="this.style.borderColor='var(--terracotta)'"
                   onblur="this.style.borderColor='var(--sand)'">
        </div>
        <select name="status" onchange="this.form.submit()"
                style="padding:8px 12px;border:1.5px solid var(--sand);border-radius:var(--r-md);background:#fff;font-family:var(--fb);font-size:13px;color:var(--soil);outline:none;cursor:pointer">
            <option value="">Semua Status</option>
            <option value="pending"  {{ request('status')==='pending'  ? 'selected':'' }}>Pending</option>
            <option value="aktif"    {{ request('status')==='aktif'    ? 'selected':'' }}>Aktif</option>
            <option value="selesai"  {{ request('status')==='selesai'  ? 'selected':'' }}>Selesai</option>
            <option value="batal"    {{ request('status')==='batal'    ? 'selected':'' }}>Batal</option>
        </select>
        <input type="date" name="tgl" value="{{ request('tgl') }}"
               style="padding:8px 12px;border:1.5px solid var(--sand);border-radius:var(--r-md);background:#fff;font-family:var(--fb);font-size:13px;color:var(--soil);outline:none">
    </form>
</div>

<div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);overflow:hidden;border:1px solid rgba(176,139,110,.07)">
    <div style="padding:14px 20px;border-bottom:1px solid rgba(176,139,110,.09);display:flex;align-items:center;justify-content:space-between">
        <div style="font-family:var(--fd);font-size:15.5px;font-weight:500;color:var(--soil)">Semua Booking</div>
        <span style="font-size:13px;color:var(--clay)">{{ $bookings->total() }} booking</span>
    </div>

    <table class="data-tbl">
        <thead>
            <tr>
                <th>No. Booking</th>
                <th>Pelanggan</th>
                <th>Alat</th>
                <th>Periode Sewa</th>
                <th>Durasi</th>
                <th>Total</th>
                <th>Denda</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $b)
            <tr>
                <td>
                    <strong style="font-size:13px">{{ $b->nomor_booking }}</strong>
                    <div style="font-size:11px;color:var(--clay)">{{ $b->created_at->format('d M Y') }}</div>
                </td>
                <td>
                    <div style="font-size:13px;font-weight:600;color:var(--soil)">{{ $b->user->name }}</div>
                    <div style="font-size:11.5px;color:var(--clay)">{{ $b->user->telepon ?? '-' }}</div>
                </td>
                <td style="font-size:13px;color:var(--soil-light)">{{ $b->alat->nama }}</td>
                <td style="font-size:12.5px">
                    <div>{{ $b->tanggal_mulai->format('d M Y') }}</div>
                    <div style="color:var(--clay)">→ {{ $b->tanggal_selesai->format('d M Y') }}</div>
                    @if($b->status === 'aktif' && $b->tanggal_selesai->isPast())
                        <div style="color:#c03030;font-weight:700;font-size:11px">⚠ TERLAMBAT</div>
                    @endif
                </td>
                <td style="text-align:center">
                    <span style="font-weight:700;color:var(--soil)">{{ $b->durasi_hari }}</span>
                    <span style="font-size:11px;color:var(--clay)"> hari</span>
                </td>
                <td style="font-weight:700;color:var(--soil)">Rp {{ number_format($b->total_bayar,0,',','.') }}</td>
                <td>
                    @if($b->denda > 0)
                        <span style="color:#c03030;font-weight:700;font-size:13px">Rp {{ number_format($b->denda,0,',','.') }}</span>
                        <div style="font-size:11px;color:var(--clay)">{{ $b->hari_terlambat }} hari</div>
                    @else
                        <span style="color:var(--concrete);font-size:13px">—</span>
                    @endif
                </td>
                <td>
                    @php $bc = match($b->status) {
                        'aktif'=>'s-proses','selesai'=>'s-lunas',
                        'batal'=>'s-batal',default=>'s-pending'
                    }; @endphp
                    <span class="status-pill {{ $bc }}">{{ ucfirst($b->status) }}</span>
                    @if($b->tanggal_kembali_aktual)
                        <div style="font-size:11px;color:var(--clay);margin-top:3px">
                            Kembali: {{ $b->tanggal_kembali_aktual->format('d M Y') }}
                        </div>
                    @endif
                </td>
                <td>
                    <div class="act-btns">
                        @if($b->status === 'aktif')
                        <form action="{{ route('admin.sewa.booking.selesai', $b->id) }}" method="POST"
                              onsubmit="return confirm('Tandai booking {{ $b->nomor_booking }} selesai?')">
                            @csrf @method('PATCH')
                            <button type="submit" class="act-btn" title="Selesaikan"
                                    style="background:rgba(96,108,56,.08)">
                                <svg viewBox="0 0 24 24" stroke="var(--moss)">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </button>
                        </form>
                        <button onclick="modalDenda({{ $b->id }}, '{{ $b->nomor_booking }}')"
                                class="act-btn" title="Catat Denda">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                            </svg>
                        </button>
                        @endif
                        <a href="https://wa.me/{{ $b->user->telepon ?? '' }}" target="_blank"
                           class="act-btn" title="WA Pelanggan">
                            <svg viewBox="0 0 24 24">
                                <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/>
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center;padding:40px;color:var(--clay)">
                    Tidak ada data booking
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 20px;border-top:1px solid rgba(176,139,110,.09);background:var(--oat)">
        <div style="font-size:13px;color:var(--clay)">
            {{ $bookings->firstItem() ?? 0 }}–{{ $bookings->lastItem() ?? 0 }} dari {{ $bookings->total() }}
        </div>
        <div style="display:flex;gap:4px">
            @if(!$bookings->onFirstPage())
                <a href="{{ $bookings->previousPageUrl() }}" class="pag-btn">‹</a>
            @endif
            @foreach($bookings->getUrlRange(max(1,$bookings->currentPage()-2), min($bookings->lastPage(),$bookings->currentPage()+2)) as $p=>$u)
                @if($p==$bookings->currentPage())<button class="pag-btn active">{{$p}}</button>
                @else<a href="{{ $u }}" class="pag-btn">{{$p}}</a>@endif
            @endforeach
            @if($bookings->hasMorePages())
                <a href="{{ $bookings->nextPageUrl() }}" class="pag-btn">›</a>
            @endif
        </div>
    </div>
</div>

{{-- Modal Denda --}}
<div id="modalDenda"
     style="display:none;position:fixed;inset:0;background:rgba(44,26,14,.55);z-index:9999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:var(--r-xl);padding:32px;width:100%;max-width:400px;box-shadow:var(--sh-lg)">
        <div style="font-family:var(--fd);font-size:20px;font-weight:500;color:var(--soil);margin-bottom:6px">Catat Denda</div>
        <div id="dendaBookingLabel" style="font-size:13px;color:var(--clay);margin-bottom:20px">—</div>
        <form id="dendaForm" method="POST">
            @csrf @method('PATCH')
            <div class="form-grp">
                <label class="form-lbl">Jumlah Denda (Rp) *</label>
                <input type="number" name="denda" id="dendaInput"
                       class="form-inp" placeholder="100000" min="0" required>
            </div>
            <div style="display:flex;gap:10px">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">
                    💾 Simpan Denda
                </button>
                <button type="button" onclick="tutupDenda()"
                        class="btn btn-secondary" style="flex:1;justify-content:center">Batal</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function modalDenda(id, nomor) {
    document.getElementById('dendaBookingLabel').textContent = 'Booking: ' + nomor;
    document.getElementById('dendaForm').action = `/admin/sewa/booking/${id}/denda`;
    document.getElementById('dendaInput').value = '';
    const m = document.getElementById('modalDenda');
    m.style.display = 'flex';
}
function tutupDenda() {
    document.getElementById('modalDenda').style.display = 'none';
}
document.getElementById('modalDenda')?.addEventListener('click', function(e) {
    if (e.target === this) tutupDenda();
});
</script>
@endpush