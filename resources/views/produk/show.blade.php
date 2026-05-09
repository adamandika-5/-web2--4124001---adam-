@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <span class="inline-block bg-teal-100 text-teal-800 text-sm px-4 py-1 rounded-full mb-3">
                        {{ $produk->kategori ?? 'Umum' }}
                    </span>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $produk->nama }}</h1>
                </div>
                
                <span class="text-sm px-3 py-1 rounded-full {{ $produk->aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $produk->aktif ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Harga</p>
                    <p class="text-3xl font-bold text-teal-600">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </p>
                </div>
                
                <div>
                    <p class="text-gray-500 text-sm mb-1">Stok Tersedia</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $produk->stok }} unit</p>
                </div>
            </div>

            <div class="mb-6">
                <p class="text-gray-500 text-sm mb-2">Deskripsi</p>
                <p class="text-gray-700 leading-relaxed">
                    {{ $produk->deskripsi ?? 'Tidak ada deskripsi.' }}
                </p>
            </div>

            <div class="border-t pt-6">
                <p class="text-gray-500 text-sm">
                    Ditambahkan: {{ $produk->created_at->format('d M Y, H:i') }}
                </p>
                <p class="text-gray-500 text-sm">
                    Terakhir diupdate: {{ $produk->updated_at->format('d M Y, H:i') }}
                </p>
            </div>

            <div class="flex gap-3 mt-6">
                <a href="{{ route('produk.edit', $produk->id) }}"
                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg">
                    Edit Produk
                </a>
                
                <a href="{{ route('produk.index') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection