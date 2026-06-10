<?php

namespace App\Http\Controllers;

use App\Models\{Pesanan, PesananItem, Voucher, OngkirZona, ActivityLog};
use Illuminate\Http\Request;
use Cart;

class CheckoutController extends Controller
{
    public function selectItems(Request $request)
    {
        $selected = $request->input('selected_items', []);
        if (empty($selected)) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Pilih minimal 1 produk untuk checkout.');
        }

        session(['checkout_items' => $selected]);

        return redirect()->route('checkout.index');
    }

    public function index()
    {
        if (Cart::isEmpty()) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Keranjang belanja masih kosong.');
        }

        $selectedIds = session('checkout_items', []);
        if (empty($selectedIds)) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Pilih minimal 1 produk untuk checkout.');
        }

        $items = Cart::getContent()->filter(function ($item) use ($selectedIds) {
            return in_array($item->id, $selectedIds);
        });

        if ($items->isEmpty()) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Pilih minimal 1 produk untuk checkout.');
        }

        $checkoutSubtotal = 0;
        $checkoutQuantity = 0;
        foreach ($items as $item) {
            $checkoutSubtotal += (float)$item->price * $item->quantity;
            $checkoutQuantity += $item->quantity;
        }

        $alamats = auth()->user()->alamat()->get();

        // Tentukan metode pengiriman yang tersedia berdasarkan produk terpilih
        $produkIds   = $items->pluck('id')->unique()->all();
        $produks     = \App\Models\Produk::whereIn('id', $produkIds)->pluck('jenis_pengiriman', 'id');

        // Kumpulkan semua nilai jenis_pengiriman dari produk terpilih
        $jenisList   = $produks->values()->unique()->all();

        $adaArmada   = in_array('armada',    $jenisList) || in_array('keduanya', $jenisList);
        $adaEkspedisi= in_array('ekspedisi', $jenisList) || in_array('keduanya', $jenisList);
        $hanyaArmada = in_array('armada', $jenisList);
        $hanyaEksp   = in_array('ekspedisi', $jenisList) && !in_array('armada', $jenisList);

        if ($hanyaArmada) {
            $tampilArmada   = true;
            $tampilEkspedisi = false;
        } elseif ($hanyaEksp) {
            $tampilArmada   = false;
            $tampilEkspedisi = true;
        } else {
            $tampilArmada   = $adaArmada;
            $tampilEkspedisi = $adaEkspedisi;
        }

        $defaultPengiriman = $tampilArmada ? 'armada' : 'ekspedisi';

        return view('pages.checkout', compact('items', 'checkoutSubtotal', 'checkoutQuantity', 'alamats', 'tampilArmada', 'tampilEkspedisi', 'defaultPengiriman'));
    }

    public function proses(Request $request)
    {
        $request->validate([
            'alamat_id'        => 'required|exists:alamat_users,id',
            'jenis_pengiriman' => 'required|in:ekspedisi,armada',
            'metode_bayar'     => 'required|in:transfer_bank,qris,cod,dp',
            'ekspedisi'        => 'required_if:jenis_pengiriman,ekspedisi|nullable|in:jnt,jne,sicepat',
        ]);

        $selectedIds = session('checkout_items', []);
        if (empty($selectedIds)) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Pilih minimal 1 produk untuk checkout.');
        }

        $items = Cart::getContent()->filter(function ($item) use ($selectedIds) {
            return in_array($item->id, $selectedIds);
        });

        if ($items->isEmpty()) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Pilih minimal 1 produk untuk checkout.');
        }

        $alamat   = auth()->user()->alamat()->findOrFail($request->alamat_id);
        
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += (float)$item->price * $item->quantity;
        }

        // Hitung diskon voucher
        $diskonVoucher = 0;
        $voucherId     = null;

        if ($request->filled('kode_voucher')) {
            $kodeVoucher = strtoupper(trim($request->kode_voucher));
            $voucher     = Voucher::where('kode', $kodeVoucher)
                ->where('aktif', true)
                ->where(function ($q) {
                    $q->whereNull('berlaku_mulai')->orWhere('berlaku_mulai', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('berlaku_sampai')->orWhere('berlaku_sampai', '>=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('kuota')->orWhereRaw('terpakai < kuota');
                })
                ->first();

            if ($voucher) {
                $diskonVoucher = $voucher->hitungDiskon($subtotal);
                $voucherId     = $voucher->id;
            }
        }

        $jenisKirim = $request->jenis_pengiriman;

        if ($jenisKirim === 'armada') {
            $alamatLengkap = strtolower($alamat->alamat_lengkap ?? '');
            $kota = strtolower($alamat->kota ?? '');

            if (str_contains($kota, 'jombang') || str_contains($alamatLengkap, 'jombang')) {
                if (str_contains($alamatLengkap, 'peterongan') || str_contains($alamatLengkap, 'jombang kota') || str_contains($alamatLengkap, 'jombang(kota)') || str_contains($alamatLengkap, 'kec. jombang')) {
                    $ongkir = 20000;
                } elseif (str_contains($alamatLengkap, 'sumobito') || str_contains($alamatLengkap, 'budug') || str_contains($alamatLengkap, 'sekitar jombang')) {
                    $ongkir = 25000;
                } else {
                    $ongkir = 35000;
                }
            } else {
                // Luar Jombang dekat: Mojokerto, Nganjuk, Kediri, Lamongan
                $luarDekat = ['mojokerto', 'nganjuk', 'kediri', 'lamongan'];
                $isLuarDekat = false;
                foreach ($luarDekat as $c) {
                    if (str_contains($kota, $c) || str_contains($alamatLengkap, $c)) {
                        $isLuarDekat = true;
                        break;
                    }
                }
                if ($isLuarDekat) {
                    $ongkir = 75000;
                } else {
                    $ongkir = 105000;
                }
            }
            $ongkir = (int) $ongkir;
            $ekspedisiSimpan = 'Armada Toko Sinar Alam';
        } else {
            $exp = $request->ekspedisi;
            $ongkir = match ($exp) {
                'jne'     => 25000,
                'jnt'     => 23000,
                'sicepat' => 20000,
                default   => 23000,
            };
            $ongkir = (int) $ongkir;
            $ekspedisiSimpan = $exp;
        }

        $diskonVoucher = (int) $diskonVoucher;
        $total = (int) ($subtotal - $diskonVoucher + $ongkir);

        $pesanan = Pesanan::create([
            'nomor_pesanan'    => Pesanan::generateNomor(),
            'user_id'          => auth()->id(),
            'voucher_id'       => $voucherId,
            'penerima'         => $alamat->penerima,
            'telepon_penerima' => $alamat->telepon,
            'alamat_pengiriman'=> $alamat->alamat_lengkap,
            'kota_tujuan'      => $alamat->kota,
            'provinsi_tujuan'  => $alamat->provinsi,
            'kode_pos'         => $alamat->kode_pos,
            'jenis_pengiriman' => $jenisKirim,
            'ekspedisi'        => $ekspedisiSimpan,
            'ongkir'           => $ongkir,
            'subtotal'         => $subtotal,
            'diskon_voucher'   => $diskonVoucher,
            'total'            => $total,
            'metode_bayar'     => $request->metode_bayar,
            'catatan'          => $request->catatan,
            'status'           => 'pending',
            'status_pembayaran'=> 'menunggu',
        ]);

        foreach ($items as $item) {
            $produk = \App\Models\Produk::find($item->id);
            PesananItem::create([
                'pesanan_id'   => $pesanan->id,
                'produk_id'    => $item->id,
                'nama_produk'  => $item->name,
                'harga_satuan' => $produk->harga,
                'harga_promo'  => $produk->harga_promo,
                'qty'          => $item->quantity,
                'satuan'       => $produk->satuan,
                'subtotal'     => $item->price * $item->quantity,
            ]);
            $produk->decrement('stok', $item->quantity);
            $produk->increment('terjual', $item->quantity);
        }

        if ($voucherId) {
            Voucher::find($voucherId)->increment('terpakai');
        }

        foreach ($selectedIds as $id) {
            Cart::remove($id);
        }
        session()->forget('checkout_items');

        ActivityLog::catat(
            'pesanan_dibuat',
            "Pesanan {$pesanan->nomor_pesanan} senilai Rp " . number_format($total) . " dibuat",
            '🛒',
            $pesanan
        );

        return redirect()->route('checkout.selesai', $pesanan->nomor_pesanan);
    }

    public function selesai(string $nomor)
    {
        $pesanan = Pesanan::where('nomor_pesanan', $nomor)
            ->where('user_id', auth()->id())
            ->with('items.produk')
            ->firstOrFail();

        return view('pages.checkout-selesai', compact('pesanan'));
    }

    public function hitungOngkir(Request $request)
    {
        $kota = strtolower($request->kota ?? '');
        $alamatLengkap = strtolower($request->alamat_lengkap ?? '');

        // Hitung ongkir berdasarkan aturan lokal Jombang
        if (str_contains($kota, 'jombang') || str_contains($alamatLengkap, 'jombang')) {
            if (str_contains($alamatLengkap, 'peterongan') || str_contains($alamatLengkap, 'jombang kota') || str_contains($alamatLengkap, 'jombang(kota)') || str_contains($alamatLengkap, 'kec. jombang')) {
                $ongkir = 20000;
            } elseif (str_contains($alamatLengkap, 'sumobito') || str_contains($alamatLengkap, 'budug') || str_contains($alamatLengkap, 'sekitar jombang')) {
                $ongkir = 25000;
            } else {
                $ongkir = 35000;
            }
        } else {
            // Luar Jombang dekat: Mojokerto, Nganjuk, Kediri, Lamongan
            $luarDekat = ['mojokerto', 'nganjuk', 'kediri', 'lamongan'];
            $isLuarDekat = false;
            foreach ($luarDekat as $c) {
                if (str_contains($kota, $c) || str_contains($alamatLengkap, $c)) {
                    $isLuarDekat = true;
                    break;
                }
            }
            if ($isLuarDekat) {
                $ongkir = 75000;
            } else {
                $ongkir = 105000;
            }
        }

        return response()->json([
            'ongkir' => (int) $ongkir,
            'zona'   => 'Lokal',
            'jarak'  => 0,
        ]);
    }

    public function cekVoucher(Request $request)
    {
        $kode     = strtoupper(trim($request->kode ?? ''));
        $subtotal = (float) ($request->subtotal ?? 0);

        if (!$kode) {
            return response()->json(['valid' => false, 'pesan' => 'Masukkan kode voucher terlebih dahulu.']);
        }

        // 1. Cari voucher tanpa filter apapun dulu (agar bisa beri pesan spesifik)
        $voucher = Voucher::where('kode', $kode)->first();

        if (!$voucher) {
            return response()->json([
                'valid' => false,
                'pesan' => "Kode voucher \"{$kode}\" tidak ditemukan.",
            ]);
        }

        // 2. Cek aktif
        if (!$voucher->aktif) {
            return response()->json([
                'valid' => false,
                'pesan' => 'Voucher ini tidak aktif.',
            ]);
        }

        // 3. Cek belum mulai
        if ($voucher->berlaku_mulai && now()->lt($voucher->berlaku_mulai)) {
            return response()->json([
                'valid' => false,
                'pesan' => 'Voucher belum berlaku. Berlaku mulai ' . $voucher->berlaku_mulai->format('d M Y') . '.',
            ]);
        }

        // 4. Cek sudah kadaluarsa
        if ($voucher->berlaku_sampai && now()->gt($voucher->berlaku_sampai)) {
            return response()->json([
                'valid' => false,
                'pesan' => 'Voucher sudah kadaluarsa sejak ' . $voucher->berlaku_sampai->format('d M Y') . '.',
            ]);
        }

        // 5. Cek kuota
        if ($voucher->kuota !== null && $voucher->terpakai >= $voucher->kuota) {
            return response()->json([
                'valid' => false,
                'pesan' => 'Kuota voucher sudah habis.',
            ]);
        }

        // 6. Cek minimal belanja
        $minBelanja = (float) ($voucher->min_belanja ?? 0);
        if ($subtotal < $minBelanja) {
            return response()->json([
                'valid' => false,
                'pesan' => 'Minimal belanja Rp ' . number_format($minBelanja, 0, ',', '.') . ' untuk menggunakan voucher ini. '
                         . '(Belanja kamu: Rp ' . number_format($subtotal, 0, ',', '.') . ')',
            ]);
        }

        // 7. Hitung diskon
        $diskon = $voucher->hitungDiskon($subtotal);

        return response()->json([
            'valid'  => true,
            'diskon' => $diskon,
            'nama'   => $voucher->nama,
            'kode'   => $voucher->kode,
            'pesan'  => "Voucher {$voucher->kode} berhasil diterapkan! Hemat Rp " . number_format($diskon, 0, ',', '.'),
        ]);
    }
}