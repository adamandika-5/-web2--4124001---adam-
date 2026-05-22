<?php

namespace App\Exports;

use App\Models\Pesanan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PesananExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Pesanan::with('user')->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No. Pesanan', 'Pelanggan', 'Email',
            'Kota Tujuan', 'Jenis Kirim', 'Ongkir',
            'Subtotal', 'Diskon', 'Total',
            'Status', 'Status Bayar', 'Tanggal',
        ];
    }

    public function map($p): array
    {
        return [
            $p->nomor_pesanan,
            $p->user->name ?? '-',
            $p->user->email ?? '-',
            $p->kota_tujuan,
            $p->jenis_pengiriman,
            $p->ongkir,
            $p->subtotal,
            $p->diskon_voucher + $p->diskon_produk,
            $p->total,
            $p->status,
            $p->status_pembayaran,
            $p->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}