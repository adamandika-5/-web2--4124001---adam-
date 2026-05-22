@extends('layouts.admin')
@section('title','Stok & Gudang')
@section('page_title','Manajemen Stok')
@section('breadcrumb','Inventaris › Stok & Gudang')

@section('content')
{{--
  Eager loading di controller:
  Produk::with('kategori')
         ->aktif()
         ->orderBy('stok')
         ->paginate(25)
--}}

{{-- Ringkasan stok --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px">
    @foreach([
        ['📦','Total Produk Aktif',$totalAktif,'rgba(198,107,61,.1)','var(--soil)'],
        ['✅','Stok Aman',$stokAman,'rgba(96,108,56,.1)','var(--moss)'],
        ['⚠️','Stok Rendah (< 20)',$stokRendah,'rgba(192,142,58,.1)','var(--ochre)'],
        ['❌','Stok Habis',$stokHabis,'rgba(192,48,48,.08)','#c03030'],
    ] as [$ikon,$label,$val,$bg,$warna])
    <div style="background:#fff;border-radius:var(--r-lg);padding:18px 20px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
        <div style="width:40px;height:40px;background:{{ $bg }};border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;font-size:20px;margin-bottom:12px">{{ $ikon }}</div>
        <div style="font-size:11px;color:var(--clay);font-weight:700;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px">{{ $label }}</div>
        <div style="font-family:var(--fd);font-size:26px;font-weight:700;color:{{ $warna }}">{{ number_format($val) }}</div>
    </div>
    @endforeach
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:16px">

    {{-- Tabel Stok --}}
    <div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);overflow:hidden;border:1px solid rgba(176,139,110,.07)">
        <div style="padding:14px 20px;border-bottom:1px solid rgba(176,139,110,.09);display:flex;align-items:center;justify-content:space-between">
            <div style="font-family:var(--fd);font-size:15.5px;font-weight:500;color:var(--soil)">Inventaris Stok</div>
            <form method="GET" action="{{ route('admin.stok.index') }}" style="display:flex;gap:8px">
                <select name="filter" onchange="this.form.submit()"
                        style="padding:6px 10px;border:1.5px solid var(--sand);border-radius:var(--r-md);background:#fff;font-family:var(--fb);font-size:12.5px;color:var(--soil);outline:none;cursor:pointer">
                    <option value="">Semua</option>
                    <option value="low_stock" {{ request('filter')==='low_stock'?'selected':'' }}>Stok Rendah</option>
                    <option value="out_of_stock" {{ request('filter')==='out_of_stock'?'selected':'' }}>Stok Habis</option>
                </select>
                <select name="kategori" onchange="this.form.submit()"
                        style="padding:6px 10px;border:1.5px solid var(--sand);border-radius:var(--r-md);background:#fff;font-family:var(--fb);font-size:12.5px;color:var(--soil);outline:none;cursor:pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris ?? [] as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori')==$kat->id?'selected':'' }}>{{ $kat->nama }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <table class="data-tbl">
            <thead>
                <tr>
                    <th>Produk</th><th>Kategori</th>
                    <th>Stok Saat Ini</th><th>Satuan</th>
                    <th>Status</th><th>Aksi Cepat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produks ?? [] as $p)
                <tr>
                    <td>
                        <div style="font-weight:700;font-size:13px;color:var(--soil)">{{ Str::limit($p->nama,40) }}</div>
                        <div style="font-size:11px;color:var(--clay)">SKU: {{ $p->sku ?? '—' }}</div>
                    </td>
                    <td>
                        <span class="badge" style="background:rgba(176,139,110,.1);color:var(--clay);font-size:11px">{{ $p->kategori->nama ?? '—' }}</span>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <span style="font-family:var(--fd);font-size:18px;font-weight:700;
                                  color:{{ $p->stok<=0 ? '#c03030' : ($p->stok<20 ? 'var(--ochre)' : 'var(--moss)') }}">
                                {{ number_format($p->stok) }}
                            </span>
                            @if($p->stok<=0)
                                <span style="font-size:10px;font-weight:700;background:rgba(192,48,48,.1);color:#c03030;padding:2px 6px;border-radius:20px">HABIS</span>
                            @elseif($p->stok<20)
                                <span style="font-size:10px;font-weight:700;background:rgba(192,142,58,.1);color:var(--ochre);padding:2px 6px;border-radius:20px">RENDAH</span>
                            @endif
                        </div>
                        {{-- Progress bar stok --}}
                        @php $pct = min(100, ($p->stok / max($p->stok, 100)) * 100); @endphp
                        <div style="width:100%;height:4px;background:rgba(176,139,110,.15);border-radius:2px;margin-top:5px;overflow:hidden">
                            <div style="height:100%;width:{{ $pct }}%;border-radius:2px;transition:width .4s;
                                  background:{{ $p->stok<=0 ? '#c03030' : ($p->stok<20 ? 'var(--ochre)' : 'var(--moss)') }}"></div>
                        </div>
                    </td>
                    <td style="color:var(--clay)">{{ $p->satuan }}</td>
                    <td>
                        <span class="status-pill {{ $p->stok<=0 ? 's-batal' : ($p->stok<20 ? 's-pending' : 's-lunas') }}">
                            {{ $p->stok<=0 ? 'Habis' : ($p->stok<20 ? 'Rendah' : 'Aman') }}
                        </span>
                    </td>
                    <td>
                        <button onclick="toggleRestock({{ $p->id }}, '{{ addslashes($p->nama) }}', {{ $p->stok }}, '{{ $p->satuan }}')"
                                class="btn btn-secondary btn-sm" style="font-size:11.5px;padding:5px 12px">
                            + Restock
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--clay)">Tidak ada produk ditemukan</td></tr>
                @endforelse
            </tbody>
        </table>
        @if(isset($produks) && method_exists($produks, 'currentPage'))
        <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 20px;border-top:1px solid rgba(176,139,110,.09);background:var(--oat)">
            <div style="font-size:13px;color:var(--clay)">{{ $produks->total() }} produk</div>
            <div style="display:flex;gap:4px">
                @if(!$produks->onFirstPage())<a href="{{ $produks->previousPageUrl() }}" class="pag-btn">‹</a>@endif
                @foreach($produks->getUrlRange(max(1,$produks->currentPage()-2),min($produks->lastPage(),$produks->currentPage()+2)) as $pg=>$url)
                    @if($pg==$produks->currentPage())<button class="pag-btn active">{{$pg}}</button>
                    @else<a href="{{ $url }}" class="pag-btn">{{$pg}}</a>@endif
                @endforeach
                @if($produks->hasMorePages())<a href="{{ $produks->nextPageUrl() }}" class="pag-btn">›</a>@endif
            </div>
        </div>
        @endif
    </div>

    {{-- Riwayat & Info --}}
    <div style="display:flex;flex-direction:column;gap:14px">

        {{-- Produk stok kritis --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:20px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
            <div style="font-family:var(--fd);font-size:15px;font-weight:500;color:var(--soil);margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid var(--sand);display:flex;align-items:center;justify-content:space-between">
                Perlu Restock Segera
                <span class="badge badge-low">{{ $stokRendah + $stokHabis }}</span>
            </div>
            @forelse($produkKritis ?? [] as $p)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid rgba(176,139,110,.07)">
                <div>
                    <div style="font-size:13px;font-weight:700;color:var(--soil)">{{ Str::limit($p->nama,28) }}</div>
                    <div style="font-size:11.5px;color:{{ $p->stok<=0?'#c03030':'var(--ochre)' }};font-weight:700;margin-top:2px">
                        {{ $p->stok<=0 ? '✕ Habis' : '⚠ Sisa '.$p->stok.' '.$p->satuan }}
                    </div>
                </div>
                <button onclick="toggleRestock({{ $p->id }}, '{{ addslashes($p->nama) }}', {{ $p->stok }}, '{{ $p->satuan }}')"
                        class="btn btn-secondary btn-sm" style="font-size:11px;padding:4px 10px">
                    Restock
                </button>
            </div>
            @empty
            <div style="text-align:center;padding:20px;color:var(--clay);font-size:13px">✓ Semua stok aman</div>
            @endforelse
        </div>

        {{-- Laporan Stok --}}
        <div style="background:#fff;border-radius:var(--r-lg);padding:20px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
            <div style="font-family:var(--fd);font-size:15px;font-weight:500;color:var(--soil);margin-bottom:14px">Export Laporan</div>
            <a href="{{ route('admin.stok.laporan') }}?format=excel"
               class="btn btn-secondary" style="width:100%;justify-content:center;margin-bottom:8px;font-size:13px">
                📊 Export Excel
            </a>
            <a href="{{ route('admin.stok.laporan') }}?format=pdf"
               class="btn btn-secondary" style="width:100%;justify-content:center;font-size:13px">
                📄 Export PDF
            </a>
        </div>
    </div>
</div>

{{-- Modal Restock --}}
<div id="restockModal" style="display:none;position:fixed;inset:0;background:rgba(44,26,14,.55);z-index:9999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:var(--r-xl);padding:32px;width:100%;max-width:400px;box-shadow:var(--sh-lg)">
        <div style="font-family:var(--fd);font-size:20px;font-weight:500;color:var(--soil);margin-bottom:6px" id="restockNama">—</div>
        <div style="font-size:13px;color:var(--clay);margin-bottom:20px">
            Stok saat ini: <strong id="restockStokSaatIni">—</strong>
        </div>
        <form id="restockForm" method="POST">
            @csrf @method('POST')
            <div class="form-grp">
                <label class="form-lbl">Jumlah Tambahan *</label>
                <input type="number" name="jumlah" id="restockJumlah" class="form-inp"
                       placeholder="Contoh: 100" min="1" required>
            </div>
            <div class="form-grp">
                <label class="form-lbl">Catatan</label>
                <input type="text" name="catatan" class="form-inp" placeholder="Contoh: Terima dari supplier X">
            </div>
            <div style="display:flex;gap:10px">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">💾 Tambah Stok</button>
                <button type="button" onclick="tutupRestock()" class="btn btn-secondary" style="flex:1;justify-content:center">Batal</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function toggleRestock(id, nama, stok, satuan) {
    document.getElementById('restockNama').textContent = nama;
    document.getElementById('restockStokSaatIni').textContent = stok + ' ' + satuan;
    document.getElementById('restockForm').action = `/admin/stok/${id}/tambah`;
    document.getElementById('restockJumlah').value = '';
    const m = document.getElementById('restockModal');
    m.style.display = 'flex';
}
function tutupRestock() {
    document.getElementById('restockModal').style.display = 'none';
}
document.getElementById('restockModal')?.addEventListener('click', function(e) {
    if (e.target === this) tutupRestock();
});
</script>
@endpush