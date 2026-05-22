<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StokExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Produk::with('kategori')->aktif()->orderBy('stok')->get();
    }

    public function headings(): array
    {
        return ['SKU', 'Nama Produk', 'Kategori', 'Stok', 'Satuan', 'Status Stok'];
    }

    public function map($p): array
    {
        return [
            $p->sku ?? '-',
            $p->nama,
            $p->kategori->nama ?? '-',
            $p->stok,
            $p->satuan,
            $p->stok <= 0 ? 'Habis' : ($p->stok < 20 ? 'Rendah' : 'Aman'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}