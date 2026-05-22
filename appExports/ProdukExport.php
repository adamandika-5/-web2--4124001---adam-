<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProdukExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Produk::with('kategori')->latest()->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'SKU', 'Nama Produk', 'Kategori',
            'Harga Normal', 'Harga Promo', 'Stok',
            'Satuan', 'Terjual', 'Status', 'Unggulan',
        ];
    }

    public function map($produk): array
    {
        return [
            $produk->id,
            $produk->sku ?? '-',
            $produk->nama,
            $produk->kategori->nama ?? '-',
            $produk->harga,
            $produk->harga_promo ?? '-',
            $produk->stok,
            $produk->satuan,
            $produk->terjual,
            $produk->aktif ? 'Aktif' : 'Nonaktif',
            $produk->unggulan ? 'Ya' : 'Tidak',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}