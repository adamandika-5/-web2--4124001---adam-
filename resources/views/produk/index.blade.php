@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    
    {{-- Flash Message --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
        {{ session('success') }}
    </div>
    @endif

    {{-- Header + Search + Tombol Tambah --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Katalog Produk</h1>
            <p class="text-gray-500">Total: {{ $produk->total() }} produk</p>
        </div>
        
        <div class="flex gap-3 flex-wrap">
            <form method="GET" action="{{ route('produk.index') }}" class="flex gap-2">
                <input 
                    type="text"
                    name="q" 
                    value="{{ request('q') }}"
                    placeholder="Cari produk..."
                    class="border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:outline-none">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-xl">
                    Cari
                </button>
            </form>
            
            <a href="{{ route('produk.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl">
                + Tambah
            </a>
        </div>
    </div>

    {{-- Grid Produk --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($produk as $p)
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition">
            
            {{-- Gambar Produk (jika ada) --}}
            @if($p->foto)
            <img src="{{ $p->foto }}" alt="{{ $p->nama }}" class="w-full h-48 object-cover">
            @else
            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                <span class="text-gray-400 text-sm">Tidak ada gambar</span>
            </div>
            @endif
            
            <div class="p-6">
                <span class="inline-block bg-teal-100 text-teal-800 text-xs px-3 py-1 rounded-full">
                    {{ $p->kategori ?? 'Umum' }}
                </span>
                
                <h2 class="text-lg font-bold mt-3 mb-1 text-gray-800">{{ $p->nama }}</h2>
                
                <p class="text-2xl font-bold text-teal-600 mb-2">
                    Rp {{ number_format($p->harga, 0, ',', '.') }}
                </p>
                
                <p class="text-sm text-gray-500 mb-4">Stok: {{ $p->stok }}</p>
                
                <div class="flex gap-2">
                    {{-- Tombol Detail --}}
                    <a href="{{ route('produk.show', $p->id) }}"
                       class="flex-1 text-center bg-teal-600 hover:bg-teal-700 text-white py-2 rounded-xl text-sm transition">
                        Detail
                    </a>
                    
                    {{-- Tombol Edit --}}
                    <a href="{{ route('produk.edit', $p->id) }}"
                       class="flex-1 text-center bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded-xl text-sm transition">
                        Edit
                    </a>
                    
                    {{-- Tombol Hapus --}}
                    <form action="{{ route('produk.destroy', $p->id) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus produk ini?')"
                          class="inline">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-sm transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-16">
            <p class="text-gray-500 text-lg">Produk tidak ditemukan.</p>
            <a href="{{ route('produk.create') }}" class="text-teal-600 hover:underline mt-2 inline-block">
                Tambah produk pertama
            </a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $produk->withQueryString()->links() }}
    </div>
</div>
@endsection