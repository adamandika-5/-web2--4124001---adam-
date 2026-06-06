<?php
namespace App\Http\Controllers;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->pesanan()
            ->with(['items.produk.gambar', 'pembayaran'])
            ->withCount('items')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return view('pages.pesanan', [
            'pesanans' => $query->paginate(10)->withQueryString(),
        ]);
    }

    public function show(string $nomor)
    {
        $pesanan = Pesanan::where('nomor_pesanan', $nomor)
            ->where('user_id', auth()->id())
            ->with(['items.produk.gambar', 'pembayaran', 'voucher'])
            ->firstOrFail();

        return view('pages.pesanan-detail', compact('pesanan'));
    }

    public function uploadBukti(Request $request, string $nomor)
    {
        $request->validate([
            'bukti' => 'required|image|max:3072',
            'bank'  => 'nullable|string',
            'nama_pengirim' => 'nullable|string',
        ]);

        $pesanan = Pesanan::where('nomor_pesanan', $nomor)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $path = $request->file('bukti')->store('bukti', 'public');

        $pesanan->pembayaran()->create([
            'user_id'       => auth()->id(),
            'metode'        => $request->metode ?? 'transfer_bank',
            'bank'          => $request->bank,
            'nama_pengirim' => $request->nama_pengirim,
            'jumlah'        => $pesanan->total,
            'bukti_path'    => $path,
            'status'        => 'menunggu',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu konfirmasi admin.');
    }

    public function batal(string $nomor)
    {
        $pesanan = Pesanan::where('nomor_pesanan', $nomor)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        // Kembalikan stok
        foreach ($pesanan->items as $item) {
            if ($item->produk) {
                $item->produk->increment('stok', $item->qty);
                $item->produk->decrement('terjual', $item->qty);
            }
        }

        $pesanan->update(['status' => 'batal']);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function invoice(string $nomor)
    {
        // Cari pesanan berdasarkan nomor_pesanan
        $pesanan = Pesanan::where('nomor_pesanan', $nomor)
            ->with(['items.produk', 'user', 'pembayaran', 'voucher'])
            ->firstOrFail();

        // Admin boleh download invoice siapapun, user biasa hanya miliknya sendiri
        $isAdmin = auth()->user()->role === 'admin';
        if (!$isAdmin && $pesanan->user_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengakses invoice ini.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', compact('pesanan'));
        return $pdf->download("Invoice-{$pesanan->nomor_pesanan}.pdf");
    }

    public function lacak(Request $request)
    {
        return view('pages.lacak-pesanan');
    }

    public function lacakShow(string $nomor)
    {
        $pesanan = Pesanan::where('nomor_pesanan', $nomor)
            ->with(['items.produk.gambar'])
            ->first();

        return view('pages.lacak-pesanan', [
            'pesanan'  => $pesanan,
            'notFound' => !$pesanan,
        ]);
    }
}