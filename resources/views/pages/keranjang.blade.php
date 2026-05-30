@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
@php
    $itemsList = $items ?? collect();
    try {
        $total = class_exists('\Cart') ? \Cart::getTotal() : 0;
    } catch (\Throwable $e) {
        $total = 0;
    }
@endphp

<div class="page-section" style="padding-top:32px;padding-bottom:64px">
    <div class="page-hdr">
        <div class="section-label">Pembelian</div>
        <h1 class="section-title" style="font-size:clamp(26px,3vw,38px)">Keranjang Belanja</h1>
    </div>

    @if($itemsList->isEmpty())
        <div style="text-align:center;padding:64px 24px" class="card">
            <div style="font-size:64px;margin-bottom:16px">🛒</div>
            <h2 style="font-family:var(--fs);font-size:20px;font-weight:700;color:var(--soil);margin-bottom:8px">Keranjang Belanja Kosong</h2>
            <p style="font-size:14px;color:var(--clay);margin-bottom:24px;max-width:400px;margin-left:auto;margin-right:auto">
                Anda belum menambahkan produk apa pun ke dalam keranjang belanja. Jelajahi katalog kami untuk menemukan material berkualitas.
            </p>
            <a href="{{ route('katalog.index') }}" class="btn btn-primary" style="text-decoration:none">Jelajahi Katalog</a>
        </div>
    @else
        <div class="resp-grid-cart">
            
            {{-- KOLOM KIRI: Daftar Produk --}}
            <div style="display:flex;flex-direction:column;gap:16px">
                <div class="card">
                    
                    {{-- Header Tabel / List --}}
                    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:16px;border-bottom:1.5px solid var(--sand);margin-bottom:20px;gap:12px;flex-wrap:wrap">
                        <div style="display:flex;align-items:center;gap:8px">
                            <input type="checkbox" id="selectAll" checked style="width:18px;height:18px;accent-color:var(--terracotta);cursor:pointer">
                            <label for="selectAll" style="font-size:14px;font-weight:700;color:var(--soil);cursor:pointer">Pilih Semua</label>
                        </div>
                        
                        <form action="{{ route('keranjang.kosongkan') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan keranjang belanja?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:none;border:none;color:#c03030;font-size:13px;font-weight:600;cursor:pointer;font-family:var(--fb);display:flex;align-items:center;gap:6px">
                                🗑️ Kosongkan Keranjang
                            </button>
                        </form>
                    </div>

                    {{-- List Produk --}}
                    <div style="display:flex;flex-direction:column;gap:20px">
                        @foreach($itemsList as $entry)
                            @php
                                $item = $entry['item'] ?? null;
                                $produk = $entry['produk'] ?? null;
                            @endphp
                            
                            @if($item)
                                @php
                                    $harga = $item->price;
                                    $subtotal = $harga * $item->quantity;
                                    $gambar = $item->attributes->gambar ?? null;
                                    $satuan = $item->attributes->satuan ?? ($produk ? $produk->satuan : 'pcs');
                                @endphp
                                <div style="display:flex;gap:14px;align-items:center;padding-bottom:20px;border-bottom:1px solid var(--sand)">
                                    {{-- Checkbox Pilihan --}}
                                    <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" form="checkoutSelectForm" class="item-checkbox" data-price="{{ $harga }}" data-qty="{{ $item->quantity }}" data-has-error="{{ ($produk && $produk->stok < $item->quantity) ? 'true' : 'false' }}" checked style="width:18px;height:18px;accent-color:var(--terracotta);cursor:pointer;flex-shrink:0">

                                    {{-- Gambar Produk --}}
                                    <a href="{{ $produk ? route('produk.show', $produk->slug) : '#' }}" style="width:80px;height:80px;border-radius:var(--r-md);background:var(--oat);display:flex;align-items:center;justify-content:center;overflow:hidden;border:1px solid rgba(176,139,110,.12);flex-shrink:0">
                                        @if($gambar)
                                            <img src="{{ asset('storage/' . $gambar) }}" alt="{{ $item->name }}" style="width:100%;height:100%;object-fit:cover">
                                        @else
                                            <span style="font-size:24px">📦</span>
                                        @endif
                                    </a>

                                    {{-- Informasi Produk --}}
                                    <div style="flex:1;min-width:0">
                                        <h3 style="font-family:var(--fb);font-size:15px;font-weight:700;color:var(--soil);margin:0 0 4px">
                                            <a href="{{ $produk ? route('produk.show', $produk->slug) : '#' }}" style="color:inherit;text-decoration:none" onmouseover="this.style.color='var(--terracotta)'" onmouseout="this.style.color='inherit'">
                                                {{ $item->name }}
                                            </a>
                                        </h3>
                                        <div style="font-size:13px;color:var(--clay);margin-bottom:8px">
                                            Rp {{ number_format($harga, 0, ',', '.') }} / {{ $satuan }}
                                        </div>
                                        
                                        {{-- Peringatan Stok --}}
                                        @if($produk && $produk->stok < $item->quantity)
                                            <div style="font-size:11.5px;color:#c03030;font-weight:600;margin-top:4px">
                                                ⚠️ Stok tidak mencukupi (Tersedia: {{ $produk->stok }} {{ $satuan }})
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Qty Update & Hapus --}}
                                    <div style="display:flex;align-items:center;gap:16px;flex-shrink:0">
                                        <form action="{{ route('keranjang.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="qty-ctrl">
                                                <button type="button" class="qty-btn" onclick="decrementQty('qty_{{ $item->id }}')">−</button>
                                                <input type="number" name="qty" id="qty_{{ $item->id }}"
                                                       value="{{ $item->quantity }}"
                                                       min="1" max="{{ $produk ? $produk->stok : 9999 }}"
                                                       onchange="this.form.submit()"
                                                       class="qty-input">
                                                <button type="button" class="qty-btn" onclick="incrementQty('qty_{{ $item->id }}')">+</button>
                                            </div>
                                        </form>

                                        <div style="text-align:right;min-width:110px">
                                            <div style="font-size:14.5px;font-weight:700;color:var(--soil)">
                                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                                            </div>
                                        </div>

                                        <form action="{{ route('keranjang.hapus', $item->id) }}" method="POST" onsubmit="return confirm('Hapus {{ $item->name }} dari keranjang?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background:none;border:none;color:var(--clay);cursor:pointer;padding:6px;font-size:16px" onmouseover="this.style.color='#c03030'" onmouseout="this.style.color='var(--clay)'" title="Hapus produk">
                                                ✕
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    
                    {{-- Kembali Belanja --}}
                    <div style="margin-top:20px">
                        <a href="{{ route('katalog.index') }}" style="font-size:13.5px;color:var(--terracotta);font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px">
                            ← Kembali Belanja
                        </a>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Ringkasan Belanja --}}
            <div class="cart-summary" style="position:sticky;top:90px">
                <div class="card" style="margin-top:0">
                    <h2 class="card-hdr" style="font-size:16px;font-weight:600">Ringkasan Belanja</h2>
                    
                    <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:20px">
                        <div style="display:flex;justify-content:space-between;font-size:13.5px;color:var(--clay)">
                            <span>Subtotal</span>
                            <span id="subtotalDisplay" style="font-weight:600;color:var(--soil)">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;font-size:13.5px;color:var(--clay)">
                            <span>Diskon</span>
                            <span style="font-weight:600;color:var(--moss)">- Rp 0</span>
                        </div>
                        <div style="border-top:1px dashed var(--sand);padding-top:12px;display:flex;justify-content:space-between;font-size:15px;font-weight:700;color:var(--soil)">
                            <span>Total Harga</span>
                            <span id="totalDisplay" style="color:var(--terracotta);font-weight:700">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Form untuk mengirim item terpilih ke Checkout --}}
                    <form action="{{ route('checkout.select') }}" method="POST" id="checkoutSelectForm">
                        @csrf
                    </form>

                    <div id="validationWarning" style="background:#fee2e2;border:1.5px solid #fca5a5;padding:12px;border-radius:var(--r-md);color:#991b1b;font-size:12.5px;margin-bottom:16px;line-height:1.5;display:none">
                        ⚠️ Pilih minimal 1 produk untuk checkout.
                    </div>

                    <div id="stokWarning" style="background:#fee2e2;border:1.5px solid #fca5a5;padding:12px;border-radius:var(--r-md);color:#991b1b;font-size:12.5px;margin-bottom:16px;line-height:1.5;display:none">
                        ⚠️ Terdapat produk pilihan dengan jumlah melebihi stok yang tersedia. Harap sesuaikan jumlah produk terlebih dahulu.
                    </div>

                    <button type="submit" id="checkoutBtn" form="checkoutSelectForm" class="btn btn-primary" style="width:100%;justify-content:center;font-size:14px;padding:12px;text-align:center;text-decoration:none">
                        Lanjut ke Checkout
                    </button>
                </div>
            </div>

        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function decrementQty(inputId) {
        const input = document.getElementById(inputId);
        let val = parseInt(input.value) || 1;
        if (val > 1) {
            input.value = val - 1;
            input.form.submit();
        }
    }
    
    function incrementQty(inputId) {
        const input = document.getElementById(inputId);
        let val = parseInt(input.value) || 1;
        let max = parseInt(input.max) || 9999;
        if (val < max) {
            input.value = val + 1;
            input.form.submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const subtotalDisplay = document.getElementById('subtotalDisplay');
        const totalDisplay = document.getElementById('totalDisplay');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const validationWarning = document.getElementById('validationWarning');
        const stokWarning = document.getElementById('stokWarning');

        function updateSummary() {
            let total = 0;
            let hasCheckedError = false;
            let checkedCount = 0;

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    checkedCount++;
                    const price = parseFloat(cb.getAttribute('data-price')) || 0;
                    const qty = parseInt(cb.getAttribute('data-qty')) || 0;
                    total += price * qty;
                    if (cb.getAttribute('data-has-error') === 'true') {
                        hasCheckedError = true;
                    }
                }
            });

            // Update display harga
            const formattedTotal = 'Rp ' + total.toLocaleString('id-ID');
            if (subtotalDisplay) subtotalDisplay.textContent = formattedTotal;
            if (totalDisplay) totalDisplay.textContent = formattedTotal;

            // Kontrol tombol checkout & validasi
            if (checkedCount === 0) {
                if (validationWarning) validationWarning.style.display = 'block';
                if (stokWarning) stokWarning.style.display = 'none';
                if (checkoutBtn) {
                    checkoutBtn.disabled = true;
                    checkoutBtn.style.opacity = '0.6';
                    checkoutBtn.style.cursor = 'not-allowed';
                }
            } else {
                if (validationWarning) validationWarning.style.display = 'none';
                if (hasCheckedError) {
                    if (stokWarning) stokWarning.style.display = 'block';
                    if (checkoutBtn) {
                        checkoutBtn.disabled = true;
                        checkoutBtn.style.opacity = '0.6';
                        checkoutBtn.style.cursor = 'not-allowed';
                    }
                } else {
                    if (stokWarning) stokWarning.style.display = 'none';
                    if (checkoutBtn) {
                        checkoutBtn.disabled = false;
                        checkoutBtn.style.opacity = '1';
                        checkoutBtn.style.cursor = 'pointer';
                    }
                }
            }

            // Sync status checkbox "Pilih Semua"
            if (selectAll) {
                selectAll.checked = (checkedCount === checkboxes.length);
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checkboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                });
                updateSummary();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateSummary);
        });

        // Hitung ulang pertama kali halaman dimuat
        updateSummary();
    });
</script>
@endpush
