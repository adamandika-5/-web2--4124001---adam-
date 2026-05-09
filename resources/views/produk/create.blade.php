@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-2xl mx-auto py-8">

    {{-- Alert Error --}}
    @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-300 bg-red-50 p-4">
            <h3 class="font-semibold text-red-600">
                Terjadi kesalahan:
            </h3>

            <ul class="mt-2 list-disc pl-5 text-sm text-red-500">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-card title="Form Tambah Produk Toko Bangunan">

        <form
            action="{{ route('produk.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-5"
        >
            @csrf

            {{-- Nama Produk --}}
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">
                    Nama Produk
                </label>

                <input
                    type="text"
                    name="nama"
                    value="{{ old('nama') }}"
                    required
                    placeholder="Contoh: Semen Tiga Roda"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500"
                >

                @error('nama')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kategori --}}
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">
                    Kategori
                </label>

                <input
                    type="text"
                    name="kategori"
                    value="{{ old('kategori') }}"
                    required
                    placeholder="Contoh: Semen, Cat, Pasir"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500"
                >

                @error('kategori')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Harga dan Stok --}}
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">
                        Harga
                    </label>

                    <input
                        type="number"
                        name="harga"
                        value="{{ old('harga') }}"
                        required
                        placeholder="Contoh: 75000"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    >

                    @error('harga')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">
                        Stok
                    </label>

                    <input
                        type="number"
                        name="stok"
                        value="{{ old('stok') }}"
                        required
                        placeholder="Contoh: 100"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    >

                    @error('stok')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Upload Gambar --}}
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">
                    Gambar Produk
                </label>

                <input
                    type="file"
                    name="gambar"
                    accept="image/*"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2"
                >

                @error('gambar')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">
                    Deskripsi
                </label>

                <textarea
                    name="deskripsi"
                    rows="4"
                    placeholder="Tuliskan deskripsi singkat produk"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500"
                >{{ old('deskripsi') }}</textarea>

                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3">

                <button
                    type="submit"
                    class="rounded-lg bg-teal-600 px-5 py-2 text-white hover:bg-teal-700"
                >
                    Simpan Produk
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