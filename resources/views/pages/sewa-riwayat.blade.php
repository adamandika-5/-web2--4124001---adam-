{{-- ================================================================
     pages/sewa-riwayat.blade.php
     ================================================================ --}}

@extends('layouts.app')
@section('title', 'Riwayat Sewa Alat')

@section('content')
<div style="max-width:1000px;margin:0 auto;padding:36px 48px">

    <div style="font-family:var(--fd);font-size:26px;font-weight:500;color:var(--soil);margin-bottom:24px">
        Riwayat Sewa Alat
    </div>

    {{-- Filter --}}
    <div style="display:flex;gap:4px;background:var(--sand);border-radius:var(--r-md);padding:4px;margin-bottom:24px;width:fit-content;flex-wrap:wrap">
        @foreach([['','Semua'],['aktif','Aktif'],['selesai','Selesai'],['batal','Batal']] as [$val,$label])
        <a href="{{ route('sewa.riwayat', ['status'=>$val]) }}"
           style="padding:7px 16px;border-radius:10px;font-size:13px;font-weight:{{ request('status')===$val||($val===''&&!request('status'))?'700':'500' }};text-decoration:none;transition:all .2s;
                  background:{{ request('status')===$val||($val===''&&!request('status'))?'#fff':'transparent' }};
                  color:{{ request('status')===$val||($val===''&&!request('status'))?'var(--soil)':'var(--clay)' }};
                  box-shadow:{{ request('status')===$val||($val===''&&!request('status'))?'var(--sh-sm)':'none' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{--
      Eager loading di controller:
      auth()->user()->bookings()
          ->with(['alat'])
          ->latest()->paginate(10)
    --}}
    @forelse($bookings as $booking)
    <div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.08);margin-bottom:14px;overflow:hidden">

        {{-- Header --}}
        <div style="background:var(--oat);padding:14px 20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;border-bottom:1px solid rgba(176,139,110,.1)">
            <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap">
                <div>
                    <span style="font-size:11px;color:var(--clay);font-weight:700;text-transform:uppercase;letter-spacing:.04em">No. Booking</span>
                    <div style="font-size:14px;font-weight:700;color:var(--soil)">{{ $booking->nomor_booking }}</div>
                </div>
                <div style="width:1px;height:30px;background:rgba(176,139,110,.2)"></div>
                <div>
                    <span style="font-size:11px;color:var(--clay);font-weight:700;text-transform:uppercase;letter-spacing:.04em">Booking</span>
                    <div style="font-size:13px;color:var(--soil)">{{ $booking->created_at->isoFormat('D MMM Y') }}</div>
                </div>
            </div>
            @php $bc = match($booking->status) {
                'aktif'=>'s-proses','selesai'=>'s-lunas','batal'=>'s-batal',default=>'s-pending'
            }; @endphp
            <span class="status-pill {{ $bc }}" style="font-size:13px">
                {{ match($booking->status) {'aktif'=>'⚙ Aktif','selesai'=>'✓ Selesai','batal'=>'✕ Batal',default=>'⏳ Pending'} }}
            </span>
        </div>

        {{-- Konten --}}
        <div style="padding:18px 20px;display:flex;gap:20px;flex-wrap:wrap">
            <div style="width:64px;height:64px;background:var(--oat);border-radius:var(--r-md);display:flex;align-items:center;justify-content:center;font-size:28px;flex-shrink:0;overflow:hidden">
                @if($booking->alat->gambar)
                    <img src="{{ asset('storage/'.$booking->alat->gambar) }}" style="width:100%;height:100%;object-fit:cover">
                @else
                    🔧
                @endif
            </div>
            <div style="flex:1;min-width:200px">
                <div style="font-family:var(--fd);font-size:16px;font-weight:500;color:var(--soil);margin-bottom:8px">
                    {{ $booking->alat->nama }}
                </div>
                <div style="display:flex;gap:20px;flex-wrap:wrap;font-size:13px;color:var(--clay)">
                    <span>📅 {{ $booking->tanggal_mulai->isoFormat('D MMM Y') }} → {{ $booking->tanggal_selesai->isoFormat('D MMM Y') }}</span>
                    <span>⏱ {{ $booking->durasi_hari }} hari</span>
                    <span>💰 Rp {{ number_format($booking->tarif_per_hari,0,',','.') }}/hari</span>
                </div>
                @if($booking->status==='aktif' && $booking->tanggal_selesai->isPast())
                    <div style="margin-top:6px;font-size:12px;font-weight:700;color:#c03030">
                        ⚠ Terlambat {{ today()->diffInDays($booking->tanggal_selesai) }} hari — segera kembalikan
                    </div>
                @endif
                @if($booking->denda > 0)
                    <div style="margin-top:6px;font-size:12px;color:#c03030;font-weight:700">
                        Denda: Rp {{ number_format($booking->denda,0,',','.') }}
                    </div>
                @endif
            </div>
            <div style="text-align:right;flex-shrink:0">
                <div style="font-size:11.5px;color:var(--clay);margin-bottom:3px">Total Biaya</div>
                <div style="font-family:var(--fd);font-size:18px;font-weight:700;color:var(--terracotta)">
                    Rp {{ number_format($booking->total_bayar,0,',','.') }}
                </div>
                @if($booking->deposit > 0)
                <div style="font-size:11px;color:var(--clay)">
                    Deposit: Rp {{ number_format($booking->deposit,0,',','.') }}
                </div>
                @endif
            </div>
        </div>

        {{-- Footer --}}
        <div style="padding:12px 20px;border-top:1px solid rgba(176,139,110,.08);background:rgba(250,247,240,.5);display:flex;align-items:center;gap:10px">
            <a href="https://wa.me/{{ config('app.whatsapp_number') }}?text=Halo+Sinar+Alam,+booking+{{ urlencode($booking->nomor_booking) }}"
               target="_blank" class="btn btn-secondary btn-sm" style="font-size:12px">
                💬 Hubungi Kami
            </a>
            @if($booking->status === 'aktif')
                <span style="font-size:12.5px;color:var(--clay)">
                    Pengembalian: <strong>{{ $booking->tanggal_selesai->isoFormat('D MMMM Y') }}</strong>
                </span>
            @endif
        </div>
    </div>
    @empty
    <div style="text-align:center;padding:72px 40px;background:#fff;border-radius:var(--r-xl);border:1px solid rgba(176,139,110,.1)">
        <div style="font-size:52px;margin-bottom:14px">🔧</div>
        <div style="font-family:var(--fd);font-size:20px;color:var(--soil);margin-bottom:8px">Belum ada riwayat sewa</div>
        <div style="font-size:14px;color:var(--clay);margin-bottom:22px">Sewa alat bangunan sesuai kebutuhan proyek Anda</div>
        <a href="{{ route('sewa.index') }}" class="btn btn-primary">Lihat Alat Tersedia →</a>
    </div>
    @endforelse

    @if($bookings->hasPages())
    <div style="display:flex;justify-content:center;gap:6px;margin-top:24px">
        @if(!$bookings->onFirstPage())<a href="{{ $bookings->previousPageUrl() }}" class="pag-btn">‹</a>@endif
        @foreach($bookings->getUrlRange(max(1,$bookings->currentPage()-2),min($bookings->lastPage(),$bookings->currentPage()+2)) as $p=>$u)
            @if($p==$bookings->currentPage())<button class="pag-btn active">{{$p}}</button>
            @else<a href="{{$u}}" class="pag-btn">{{$p}}</a>@endif
        @endforeach
        @if($bookings->hasMorePages())<a href="{{ $bookings->nextPageUrl() }}" class="pag-btn">›</a>@endif
    </div>
    @endif
</div>
@endsection