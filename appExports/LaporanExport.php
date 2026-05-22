<?php

namespace App\Exports;

use App\Models\Pesanan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Pesanan::with('user')
            ->where('status', 'selesai')
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'No. Pesanan', 'Pelanggan', 'Kota',
            'Subtotal', 'Diskon', 'Ongkir', 'Total',
            'Jenis Kirim', 'Tanggal Selesai',
        ];
    }

    public function map($p): array
    {
        return [
            $p->nomor_pesanan,
            $p->user->name ?? '-',
            $p->kota_tujuan,
            $p->subtotal,
            $p->diskon_voucher + $p->diskon_produk,
            $p->ongkir,
            $p->total,
            $p->jenis_pengiriman === 'armada' ? 'Armada Sendiri' : 'Ekspedisi',
            $p->selesai_at?->format('d/m/Y') ?? $p->updated_at->format('d/m/Y'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}