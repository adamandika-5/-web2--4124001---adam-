@extends('layouts.admin')
@section('title','Pengaturan')
@section('page_title','Pengaturan Toko')
@section('breadcrumb','Sistem › Pengaturan')

@section('content')
{{-- Pengaturan::get() sudah memanggil semua config dari DB --}}

<form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div style="display:grid;grid-template-columns:220px 1fr;gap:24px">

        {{-- Tab nav --}}
        <div style="position:sticky;top:78px;height:fit-content">
            <div style="background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-sm);overflow:hidden;border:1px solid rgba(176,139,110,.07)">
                @foreach([
                    ['umum','🏪','Informasi Toko'],
                    ['kontak','📞','Kontak & Lokasi'],
                    ['pembayaran','💳','Pembayaran'],
                    ['pengiriman','🚚','Pengiriman'],
                    ['notifikasi','🔔','Notifikasi'],
                    ['seo','🔍','SEO & Tampilan'],
                ] as [$id,$ikon,$label])
                <button type="button" onclick="gantiTab('{{ $id }}')"
                        id="tab-btn-{{ $id }}"
                        style="display:flex;align-items:center;gap:10px;padding:12px 16px;width:100%;border:none;background:#fff;cursor:pointer;font-family:var(--fb);font-size:13.5px;font-weight:600;color:var(--soil-light);text-align:left;border-bottom:1px solid rgba(176,139,110,.07);transition:all .2s">
                    <span>{{ $ikon }}</span> {{ $label }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- Konten tab --}}
        <div>

            {{-- Umum --}}
            <div id="tab-umum" class="tab-panel" style="background:#fff;border-radius:var(--r-lg);padding:28px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
                <div style="font-family:var(--fd);font-size:18px;font-weight:500;color:var(--soil);margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid var(--sand)">
                    🏪 Informasi Toko
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                    <div class="form-grp" style="grid-column:1/-1">
                        <label class="form-lbl">Nama Toko *</label>
                        <input class="form-inp" type="text" name="settings[nama_toko]"
                               value="{{ \App\Models\Pengaturan::get('nama_toko','Sinar Alam') }}" required>
                    </div>
                    <div class="form-grp" style="grid-column:1/-1">
                        <label class="form-lbl">Deskripsi Toko</label>
                        <textarea class="form-inp" name="settings[deskripsi_toko]" rows="3">{{ \App\Models\Pengaturan::get('deskripsi_toko') }}</textarea>
                    </div>
                    <div class="form-grp">
                        <label class="form-lbl">Logo Toko</label>
                        <input type="file" name="logo" accept="image/*" class="form-inp" style="padding:7px;font-size:13px">
                        @if(\App\Models\Pengaturan::get('logo'))
                            <div style="margin-top:8px"><img src="{{ asset('storage/'.\App\Models\Pengaturan::get('logo')) }}" style="height:40px;border-radius:6px"></div>
                        @endif
                    </div>
                    <div class="form-grp">
                        <label class="form-lbl">Favicon</label>
                        <input type="file" name="favicon" accept="image/*" class="form-inp" style="padding:7px;font-size:13px">
                    </div>
                    <div class="form-grp">
                        <label class="form-lbl">Mata Uang</label>
                        <select class="form-inp" name="settings[mata_uang]">
                            <option value="IDR" {{ \App\Models\Pengaturan::get('mata_uang','IDR')==='IDR'?'selected':'' }}>IDR (Rupiah)</option>
                        </select>
                    </div>
                    <div class="form-grp">
                        <label class="form-lbl">Stok Rendah Alert (< jumlah)</label>
                        <input class="form-inp" type="number" name="settings[stok_rendah_threshold]"
                               value="{{ \App\Models\Pengaturan::get('stok_rendah_threshold',20) }}" min="1">
                    </div>
                </div>
            </div>

            {{-- Kontak --}}
            <div id="tab-kontak" class="tab-panel" style="display:none;background:#fff;border-radius:var(--r-lg);padding:28px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
                <div style="font-family:var(--fd);font-size:18px;font-weight:500;color:var(--soil);margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid var(--sand)">
                    📞 Kontak & Lokasi
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                    <div class="form-grp">
                        <label class="form-lbl">No. Telepon</label>
                        <input class="form-inp" type="text" name="settings[telepon]"
                               value="{{ \App\Models\Pengaturan::get('telepon') }}" placeholder="(0343) 555-1234">
                    </div>
                    <div class="form-grp">
                        <label class="form-lbl">No. WhatsApp (dengan kode negara)</label>
                        <input class="form-inp" type="text" name="settings[whatsapp]"
                               value="{{ \App\Models\Pengaturan::get('whatsapp') }}" placeholder="628xx">
                    </div>
                    <div class="form-grp">
                        <label class="form-lbl">Email</label>
                        <input class="form-inp" type="email" name="settings[email]"
                               value="{{ \App\Models\Pengaturan::get('email') }}" placeholder="info@sinaralam.id">
                    </div>
                    <div class="form-grp">
                        <label class="form-lbl">Kota</label>
                        <input class="form-inp" type="text" name="settings[kota]"
                               value="{{ \App\Models\Pengaturan::get('kota','Jombang') }}">
                    </div>
                    <div class="form-grp" style="grid-column:1/-1">
                        <label class="form-lbl">Alamat Lengkap</label>
                        <textarea class="form-inp" name="settings[alamat]" rows="2">{{ \App\Models\Pengaturan::get('alamat') }}</textarea>
                    </div>
                    <div class="form-grp">
                        <label class="form-lbl">Google Maps Embed URL</label>
                        <input class="form-inp" type="url" name="settings[maps_url]"
                               value="{{ \App\Models\Pengaturan::get('maps_url') }}" placeholder="https://maps.google.com/...">
                    </div>
                    <div class="form-grp">
                        <label class="form-lbl">Jam Operasional</label>
                        <input class="form-inp" type="text" name="settings[jam_ops]"
                               value="{{ \App\Models\Pengaturan::get('jam_ops','Senin–Sabtu 07.00–17.00') }}">
                    </div>
                </div>
            </div>

            {{-- Pembayaran --}}
            <div id="tab-pembayaran" class="tab-panel" style="display:none;background:#fff;border-radius:var(--r-lg);padding:28px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
                <div style="font-family:var(--fd);font-size:18px;font-weight:500;color:var(--soil);margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid var(--sand)">
                    💳 Informasi Pembayaran
                </div>
                @foreach([
                    ['bca','BCA','','',''],
                    ['bni','BNI','','',''],
                    ['mandiri','Mandiri','','',''],
                    ['bri','BRI','','',''],
                ] as [$id,$nama])
                <div style="background:var(--oat);border-radius:var(--r-md);padding:16px;margin-bottom:12px;border:1px solid rgba(176,139,110,.12)">
                    <div style="font-size:13.5px;font-weight:700;color:var(--soil);margin-bottom:12px">🏦 Bank {{ $nama }}</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px">
                        <div class="form-grp" style="margin-bottom:0">
                            <label class="form-lbl">No. Rekening</label>
                            <input class="form-inp" type="text" name="settings[bank_{{ $id }}_norek]"
                                   value="{{ \App\Models\Pengaturan::get('bank_'.$id.'_norek') }}" placeholder="xxxx xxxx xxxx" style="font-size:13px">
                        </div>
                        <div class="form-grp" style="margin-bottom:0">
                            <label class="form-lbl">Atas Nama</label>
                            <input class="form-inp" type="text" name="settings[bank_{{ $id }}_nama]"
                                   value="{{ \App\Models\Pengaturan::get('bank_'.$id.'_nama') }}" placeholder="Nama pemilik rek." style="font-size:13px">
                        </div>
                        <div class="form-grp" style="margin-bottom:0">
                            <label class="form-lbl">Aktif</label>
                            <select class="form-inp" name="settings[bank_{{ $id }}_aktif]" style="font-size:13px">
                                <option value="1" {{ \App\Models\Pengaturan::get('bank_'.$id.'_aktif','1')==='1'?'selected':'' }}>Ya</option>
                                <option value="0" {{ \App\Models\Pengaturan::get('bank_'.$id.'_aktif')==='0'?'selected':'' }}>Tidak</option>
                            </select>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="form-grp" style="margin-top:16px">
                    <label class="form-lbl">QRIS — Gambar QR Code</label>
                    <input type="file" name="qris_image" accept="image/*" class="form-inp" style="padding:7px;font-size:13px">
                    @if(\App\Models\Pengaturan::get('qris_path'))
                        <img src="{{ asset('storage/'.\App\Models\Pengaturan::get('qris_path')) }}" style="height:80px;margin-top:8px;border-radius:var(--r-sm)">
                    @endif
                </div>
            </div>

            {{-- Pengiriman --}}
            <div id="tab-pengiriman" class="tab-panel" style="display:none;background:#fff;border-radius:var(--r-lg);padding:28px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
                <div style="font-family:var(--fd);font-size:18px;font-weight:500;color:var(--soil);margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid var(--sand)">
                    🚚 Konfigurasi Pengiriman
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                    <div class="form-grp">
                        <label class="form-lbl">Batas Berat Ekspedisi (gram)</label>
                        <input class="form-inp" type="number" name="settings[batas_berat_ekspedisi]"
                               value="{{ \App\Models\Pengaturan::get('batas_berat_ekspedisi',30000) }}">
                        <div style="font-size:11.5px;color:var(--clay);margin-top:4px">Di atas batas ini → wajib armada sendiri</div>
                    </div>
                    <div class="form-grp">
                        <label class="form-lbl">Minimum Order Armada (Rp)</label>
                        <input class="form-inp" type="number" name="settings[min_order_armada]"
                               value="{{ \App\Models\Pengaturan::get('min_order_armada',500000) }}">
                    </div>
                    <div class="form-grp">
                        <label class="form-lbl">Google Maps API Key</label>
                        <input class="form-inp" type="text" name="settings[gmaps_api_key]"
                               value="{{ \App\Models\Pengaturan::get('gmaps_api_key') }}" placeholder="AIza...">
                    </div>
                    <div class="form-grp">
                        <label class="form-lbl">Kota Asal Pengiriman</label>
                        <input class="form-inp" type="text" name="settings[kota_asal]"
                               value="{{ \App\Models\Pengaturan::get('kota_asal','Jombang') }}">
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div id="tab-seo" class="tab-panel" style="display:none;background:#fff;border-radius:var(--r-lg);padding:28px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
                <div style="font-family:var(--fd);font-size:18px;font-weight:500;color:var(--soil);margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid var(--sand)">
                    🔍 SEO & Tampilan
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Meta Title Beranda</label>
                    <input class="form-inp" type="text" name="settings[meta_title]"
                           value="{{ \App\Models\Pengaturan::get('meta_title','Sinar Alam — Toko Material Bangunan Jombang') }}">
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Meta Description Beranda</label>
                    <textarea class="form-inp" name="settings[meta_desc]" rows="3">{{ \App\Models\Pengaturan::get('meta_desc') }}</textarea>
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Google Analytics ID</label>
                    <input class="form-inp" type="text" name="settings[ga_id]"
                           value="{{ \App\Models\Pengaturan::get('ga_id') }}" placeholder="G-XXXXXXXXXX">
                </div>
                <div class="form-grp">
                    <label class="form-lbl">Facebook Pixel ID</label>
                    <input class="form-inp" type="text" name="settings[fb_pixel]"
                           value="{{ \App\Models\Pengaturan::get('fb_pixel') }}" placeholder="xxxxxxxxxxxxxxxx">
                </div>
            </div>

            {{-- Notifikasi --}}
            <div id="tab-notifikasi" class="tab-panel" style="display:none;background:#fff;border-radius:var(--r-lg);padding:28px;box-shadow:var(--sh-sm);border:1px solid rgba(176,139,110,.07)">
                <div style="font-family:var(--fd);font-size:18px;font-weight:500;color:var(--soil);margin-bottom:22px;padding-bottom:14px;border-bottom:1px solid var(--sand)">
                    🔔 Notifikasi
                </div>
                @foreach([
                    ['notif_email_pesanan_baru','Email notifikasi pesanan baru'],
                    ['notif_email_pembayaran','Email notifikasi pembayaran masuk'],
                    ['notif_wa_pesanan','WhatsApp notifikasi ke pelanggan'],
                    ['notif_wa_admin','WhatsApp notifikasi ke admin'],
                ] as [$key,$label])
                <label style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid rgba(176,139,110,.07);cursor:pointer">
                    <span style="font-size:13.5px;color:var(--soil)">{{ $label }}</span>
                    <input type="hidden" name="settings[{{ $key }}]" value="0">
                    <input type="checkbox" name="settings[{{ $key }}]" value="1"
                           {{ \App\Models\Pengaturan::get($key,'1')==='1'?'checked':'' }}
                           style="width:18px;height:18px;accent-color:var(--terracotta);cursor:pointer">
                </label>
                @endforeach
            </div>

            {{-- Tombol simpan --}}
            <div style="margin-top:16px;display:flex;gap:10px">
                <button type="submit" class="btn btn-primary" style="padding:12px 28px;font-size:15px">
                    💾 Simpan Pengaturan
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Batal</a>
            </div>

        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
function gantiTab(id) {
    document.querySelectorAll('.tab-panel').forEach(p => p.style.display='none');
    document.querySelectorAll('[id^="tab-btn-"]').forEach(b => {
        b.style.background='#fff'; b.style.color='var(--soil-light)';
    });
    document.getElementById('tab-'+id).style.display='block';
    const btn = document.getElementById('tab-btn-'+id);
    btn.style.background='rgba(198,107,61,.06)'; btn.style.color='var(--terracotta)';
}
gantiTab('umum');
</script>
@endpush