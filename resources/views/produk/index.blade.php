@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('content')

<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Flash Message --}}
    @if(session('success'))
        <x-alert type="success" :message="session('success')" dismissible />
    @endif

    @if(session('error'))
        <x-alert type="error" :message="session('error')" dismissible />
    @endif

    {{-- Header --}}
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">

        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Katalog Produk
            </h1>

            <p class="text-gray-500">
                Total: {{ count($produk) }} produk
            </p>
        </div>

        <div class="flex gap-3 flex-wrap">

            {{-- Search --}}
            <form method="GET" class="flex gap-2">

                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari produk..."
                    class="border rounded-xl px-4 py-2 focus:ring-2 focus:ring-teal-500"
                >

                <x-button type="submit">
                    Cari
                </x-button>

            </form>

            {{-- Tombol Tambah --}}
            <a href="{{ route('produk.create') }}"
               class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-xl">
                + Tambah
            </a>

        </div>

    </div>

    {{-- Grid Produk --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($produk as $p)

            <x-card class="group">

                {{-- Header Card --}}
                <div class="flex items-center justify-between mb-4">

                    <x-badge color="teal">
                        {{ $p['kategori'] ?? 'Umum' }}
                    </x-badge>

                    <span class="text-sm text-gray-500">
                        Stok: {{ $p['stok'] ?? 0 }}
                    </span>

                </div>

                {{-- Gambar --}}
                <img
                    src="{{ $p['gambar'] ?? 'https://via.placeholder.com/400x250' }}"
                    alt="{{ $p['nama'] ?? 'Produk' }}"
                    class="mb-4 h-48 w-full rounded-lg object-cover"
                >

                {{-- Nama --}}
                <h2 class="text-xl font-bold text-slate-800">
                    {{ $p['nama'] ?? '-' }}
                </h2>

                {{-- Deskripsi --}}
                <p class="mt-2 text-sm text-gray-600">
                    {{ $p['deskripsi'] ?? 'Tidak ada deskripsi.' }}
                </p>

                {{-- Harga --}}
                <p class="mt-4 text-2xl font-bold text-teal-600">
                    Rp {{ number_format($p['harga'] ?? 0, 0, ',', '.') }}
                </p>

                {{-- Tombol --}}
                <div class="flex gap-2 mt-5">

                    <a href="#"
                       class="flex-1 text-center bg-teal-600 hover:bg-teal-700 text-white py-2 rounded-xl text-sm">
                        Detail
                    </a>

                    <a href="#"
                       class="flex-1 text-center bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded-xl text-sm">
                        Edit
                    </a>

                    <form action="#"
                          method="POST"
                          onsubmit="return confirm('Hapus produk ini?')">

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-xl text-sm">
                            Hapus
                        </button>

                    </form>

                </div>

            </x-card>

        @empty

            <div class="col-span-3 text-center py-16">

                <p class="text-gray-500 text-lg">
                    Produk tidak ditemukan.
                </p>

            </div>

        @endforelse

    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{-- Pagination dinonaktifkan sementara --}}
    </div>

</div>

@endsection