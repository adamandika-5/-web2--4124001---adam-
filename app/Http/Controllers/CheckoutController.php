<?php

namespace App\Http\Controllers;

use App\Models\{Pesanan, PesananItem, Voucher, OngkirZona, ActivityLog};
use Illuminate\Http\Request;
use Cart;

class CheckoutController extends Controller
{
    public function index()
    {
        if (Cart::isEmpty()) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Keranjang belanja masih kosong.');
        }

        $alamats = auth()->user()->alamat()->get();

        return view('pages.checkout', compact('alamats'));
    }

    public function proses(Request $request)
    {
        $request->validate([
            'alamat_id'        => 'required|exists:alamat_users,id',
            'jenis_pengiriman' => 'required|in:ekspedisi,armada',
            'metode_bayar'     => 'required|in:transfer_bank,qris,cod,dp',
            'ekspedisi'        => 'required_if:jenis_pengiriman,ekspedisi|nullable|in:jnt,jne,sicepat',
        ]);

        $alamat   = auth()->user()->alamat()->findOrFail($request->alamat_id);
        $items    = Cart::getContent();
        $subtotal = (float) Cart::getTotal();

        // Hitung diskon voucher
        $diskonVoucher = 0;
        $voucherId     = null;

        if ($request->filled('kode_voucher')) {
            $voucher = Voucher::valid()
                ->where('kode', $request->kode_voucher)
                ->first();

            if ($voucher) {
                $diskonVoucher = $voucher->hitungDiskon($subtotal);
                $voucherId     = $voucher->id;
            }
        }

        $ongkir = (float) ($request->ongkir ?? 0);
        $total  = $subtotal - $diskonVoucher + $ongkir;

        // Buat pesanan
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
            'jenis_pengiriman' => $request->jenis_pengiriman,
            'ekspedisi'        => $request->ekspedisi,
            'ongkir'           => $ongkir,
            'subtotal'         => $subtotal,
            'diskon_voucher'   => $diskonVoucher,
            'total'            => $total,
            'catatan'          => $request->catatan,
            'status'           => 'pending',
            'status_pembayaran'=> 'menunggu',
        ]);

        // Simpan item & kurangi stok
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

        // Tambah pemakaian voucher
        if ($voucherId) {
            Voucher::find($voucherId)->increment('terpakai');
        }

        Cart::clear();

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
        $zona = OngkirZona::where('kota', 'LIKE', "%{$request->kota}%")->first();

        if (!$zona || !$zona->tersedia_armada) {
            return response()->json([
                'ongkir' => 0,
                'pesan'  => 'Area tidak tersedia untuk armada sendiri. Gunakan ekspedisi.',
            ]);
        }

        $tarif = match ($request->jenis_kendaraan ?? 'pickup') {
            'engkel' => $zona->tarif_engkel,
            'truk'   => $zona->tarif_truk,
            default  => $zona->tarif_pickup,
        };

        return response()->json([
            'ongkir' => $tarif,
            'zona'   => $zona->zona,
            'jarak'  => $zona->jarak_km,
        ]);
    }

    public function cekVoucher(Request $request)
    {
        $voucher = Voucher::valid()
            ->where('kode', strtoupper($request->kode))
            ->first();

        if (!$voucher) {
            return response()->json([
                'valid' => false,
                'pesan' => 'Kode voucher tidak valid atau sudah kadaluarsa.',
            ]);
        }

        $diskon = $voucher->hitungDiskon((float) ($request->subtotal ?? 0));

        return response()->json([
            'valid'  => true,
            'diskon' => $diskon,
            'nama'   => $voucher->nama,
            'pesan'  => "Voucher {$voucher->kode} berhasil digunakan!",
        ]);
    }
}