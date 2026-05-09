@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="max-w-2xl mx-auto py-8">

    @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-300 bg-red-50 p-4">
            <ul class="list-disc pl-5 text-sm text-red-500">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-card title="Edit Produk">

        <form
            action="{{ route('produk.update', $produk->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-5"
        >
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div>
                <label class="mb-1 block text-sm font-medium">
                    Nama Produk
                </label>

                <input
                    type="text"
                    name="nama"
                    value="{{ old('nama', $produk->nama) }}"
                    class="w-full rounded-lg border px-4 py-2"
                    required
                >
            </div>

            {{-- Kategori --}}
            <div>
                <label class="mb-1 block text-sm font-medium">
                    Kategori
                </label>

                <input
                    type="text"
                    name="kategori"
                    value="{{ old('kategori', $produk->kategori) }}"
                    class="w-full rounded-lg border px-4 py-2"
                    required
                >
            </div>

            {{-- Harga dan Stok --}}
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Harga
                    </label>

                    <input
                        type="number"
                        name="harga"
                        value="{{ old('harga', $produk->harga) }}"
                        class="w-full rounded-lg border px-4 py-2"
                        required
                    >
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Stok
                    </label>

                    <input
                        type="number"
                        name="stok"
                        value="{{ old('stok', $produk->stok) }}"
                        class="w-full rounded-lg border px-4 py-2"
                        required
                    >
                </div>

            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="mb-1 block text-sm font-medium">
                    Deskripsi
                </label>

                <textarea
                    name="deskripsi"
                    rows="4"
                    class="w-full rounded-lg border px-4 py-2"
                >{{ old('deskripsi', $produk->deskripsi) }}</textarea>
            </div>

            {{-- Gambar Lama --}}
            @if ($produk->gambar)
                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Gambar Saat Ini
                    </label>

                    <img
                        src="{{ asset('gambar/' . $produk->gambar) }}"
                        class="h-40 rounded-lg border object-cover"
                    >
                </div>
            @endif

            {{-- Upload Gambar Baru --}}
            <div>
                <label class="mb-1 block text-sm font-medium">
                    Ganti Gambar Produk
                </label>

                <input
                    type="file"
                    name="gambar"
                    accept="image/*"
                    class="w-full rounded-lg border px-4 py-2"
                >

                <p class="mt-1 text-sm text-gray-500">
                    Kosongkan jika tidak ingin mengganti gambar.
                </p>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3">

                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-5 py-2 text-white hover:bg-blue-700"
                >
                    Update Produk
                </button>

                <a
                    href="{{ route('produk.index') }}"
                    class="rounded-lg bg-gray-500 px-5 py-2 text-white hover:bg-gray-600"
                >
                    Kembali
                </a>

            </div>

        </form>

    </x-card>
</div>
@endsection