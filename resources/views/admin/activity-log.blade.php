@extends('layouts.admin')
@section('title','Activity Log')
@section('page_title','Activity Log')
@section('breadcrumb','Sistem › Activity Log')

@section('content')
{{--
  Eager loading di controller:
  ActivityLog::with('user')
              ->latest()
              ->paginate(30)
--}}

{{-- Filter --}}
<div style="display:flex;gap:10px;align-items:center;margin-bottom:16px;flex-wrap:wrap">
    <form method="GET" action="{{ route('admin.activity-log') }}" style="display:flex;gap:10px;flex:1;flex-wrap:wrap">
        <div style="position:relative;flex:1;min-width:200px">
            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--clay);pointer-events:none;width:15px;height:15px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari aktivitas, nama user..."
                   style="width:100%;padding:8px 14px 8px 36px;border:1.5px solid var(--sand);border-radius:var(--r-md);background:#fff;font-family:var(--fb);font-size:13px;color:var(--soil);outline:none;transition:border-color .2s"
                   onfocus="this.style.borderColor='var(--terracotta)'" onblur="this.style.borderColor='var(--sand)'">
        </div>
        <select name="aksi" onchange="this.form.submit()"
                style="padding:8px 12px;border:1.5px solid var(--sand);border-radius:var(--r-md);background:#fff;font-family:var(--fb);font-size:13px;color:var(--soil);outline:none;cursor:pointer">
            <option value="">Semua Aksi</option>
            @foreach(['login','logout','tambah_produk','edit_produk','hapus_produk','pesanan_dibuat','tambah_user','toggle_produk','reset_password','tambah_alat','selesai_sewa'] as $a)
                <option value="{{ $a }}" {{ request('aksi')===$a?'selected':'' }}>{{ ucwords(str_replace('_',' ',$a)) }}</option>
            @endforeach
        </select>
        <input type="date" name="tgl" value="{{ request('tgl') }}"
               style="padding:8px 12px;border:1.5px solid var(--sand);border-radius:var(--r-md);background:#fff;font-family:var(--fb);font-size:13px;color:var(--soil);outline:none">
        <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        <a href="{{ route('admin.activity-log') }}" class="btn btn-secondary btn-sm">Reset</a>
    </form>
    <form action="{{ route('admin.activity-log.hapus') }}" method="POST"
          onsubmit="return confirm('Hapus semua log? Tindakan ini tidak dapat dibatalkan.')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-secondary btn-sm" style="color:#c03030;border-color:rgba(192,48,48,.3);font-size:12px">
            🗑️ Hapus Semua Log
        </button>
    </form>
</div>

{{-- Timeline Log --}}
<div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);overflow:hidden;border:1px solid rgba(176,139,110,.07)">
    <div style="padding:14px 20px;border-bottom:1px solid rgba(176,139,110,.09);display:flex;align-items:center;justify-content:space-between">
        <div style="font-family:var(--fd);font-size:15.5px;font-weight:500;color:var(--soil)">Log Aktivitas</div>
        <span style="font-size:13px;color:var(--clay)">{{ $logs->total() }} entri</span>
    </div>

    <div style="padding:8px 0">
        @forelse($logs as $log)
        <div style="display:flex;align-items:flex-start;gap:14px;padding:14px 20px;border-bottom:1px solid rgba(176,139,110,.05);transition:background .15s"
             onmouseover="this.style.background='rgba(250,247,240,.6)'" onmouseout="this.style.background='transparent'">

            {{-- Ikon --}}
            <div style="width:36px;height:36px;background:rgba(198,107,61,.08);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;border:1.5px solid rgba(198,107,61,.12)">
                {{ $log->ikon ?? '📝' }}
            </div>

            {{-- Konten --}}
            <div style="flex:1;min-width:0">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;flex-wrap:wrap">
                    <div>
                        <span style="font-size:13.5px;color:var(--soil)">
                            <strong>{{ $log->user->name ?? 'System' }}</strong>
                            {{ $log->deskripsi }}
                        </span>
                        @if($log->model_type && $log->model_id)
                            <span style="font-size:11px;color:var(--clay);background:var(--oat);padding:2px 7px;border-radius:20px;margin-left:6px">
                                {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                            </span>
                        @endif
                    </div>
                    <div style="font-size:12px;color:var(--clay);white-space:nowrap;flex-shrink:0">
                        {{ $log->created_at->diffForHumans() }}
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:12px;margin-top:5px;flex-wrap:wrap">
                    <span style="font-size:11.5px;font-weight:700;background:rgba(176,139,110,.1);color:var(--clay);padding:2px 8px;border-radius:20px">
                        {{ ucwords(str_replace('_',' ',$log->aksi)) }}
                    </span>
                    @if($log->ip_address)
                        <span style="font-size:11px;color:var(--concrete)">IP: {{ $log->ip_address }}</span>
                    @endif
                    <span style="font-size:11px;color:var(--concrete)">{{ $log->created_at->isoFormat('D MMM Y, HH:mm:ss') }}</span>
                </div>

                {{-- Data perubahan (collapsed) --}}
                @if($log->data_lama || $log->data_baru)
                <details style="margin-top:8px">
                    <summary style="font-size:11.5px;color:var(--terracotta);cursor:pointer;font-weight:700">Lihat perubahan data</summary>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:8px">
                        @if($log->data_lama)
                        <div style="background:rgba(192,48,48,.04);border-radius:var(--r-sm);padding:10px;border:1px solid rgba(192,48,48,.12)">
                            <div style="font-size:10.5px;font-weight:700;color:#c03030;margin-bottom:5px">SEBELUM</div>
                            <pre style="font-size:11px;color:var(--soil-light);margin:0;white-space:pre-wrap;font-family:'Courier New',monospace">{{ json_encode($log->data_lama, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                        @endif
                        @if($log->data_baru)
                        <div style="background:rgba(96,108,56,.04);border-radius:var(--r-sm);padding:10px;border:1px solid rgba(96,108,56,.12)">
                            <div style="font-size:10.5px;font-weight:700;color:var(--moss);margin-bottom:5px">SESUDAH</div>
                            <pre style="font-size:11px;color:var(--soil-light);margin:0;white-space:pre-wrap;font-family:'Courier New',monospace">{{ json_encode($log->data_baru, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                        @endif
                    </div>
                </details>
                @endif
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:60px;color:var(--clay)">
            <div style="font-size:40px;margin-bottom:12px">📋</div>
            <div style="font-size:14px">Tidak ada log aktivitas ditemukan</div>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 20px;border-top:1px solid rgba(176,139,110,.09);background:var(--oat)">
        <div style="font-size:13px;color:var(--clay)">
            {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }}
        </div>
        <div style="display:flex;gap:4px">
            @if(!$logs->onFirstPage())<a href="{{ $logs->previousPageUrl() }}" class="pag-btn">‹</a>@endif
            @foreach($logs->getUrlRange(max(1,$logs->currentPage()-2),min($logs->lastPage(),$logs->currentPage()+2)) as $p=>$u)
                @if($p==$logs->currentPage())<button class="pag-btn active">{{$p}}</button>
                @else<a href="{{$u}}" class="pag-btn">{{$p}}</a>@endif
            @endforeach
            @if($logs->hasMorePages())<a href="{{ $logs->nextPageUrl() }}" class="pag-btn">›</a>@endif
        </div>
    </div>
</div>

@endsection