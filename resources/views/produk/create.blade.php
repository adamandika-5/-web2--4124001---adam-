@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    @if ($errors->any())
        <x-alert type="error" dismissible>
            Periksa kembali data produk yang diisi.
        </x-alert>
    @endif

    <x-card title="Form Tambah Produk Toko Bangunan">
        <form action="{{ route('produk.store') }}" method="POST" class="space-y-5">
            @csrf

            <x-input
                label="Nama Produk"
                name="nama"
                required
                placeholder="Contoh: Semen Tiga Roda"
                hint="Masukkan nama material bangunan."
            />

            <x-input
                label="Kategori"
                name="kategori"
                required
                placeholder="Contoh: Semen, Cat, Pasir"
            />

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <x-input
                    label="Harga"
                    name="harga"
                    type="number"
                    required
                    placeholder="Contoh: 75000"
                />

                <x-input
                    label="Stok"
                    name="stok"
                    type="number"
                    required
                    placeholder="Contoh: 100"
                />
            </div>

            <div class="space-y-1">
                <label for="deskripsi" class="block text-sm font-medium text-slate-700">
                    Deskripsi
                </label>
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="4"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    placeholder="Tuliskan deskripsi singkat produk"
                >{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <x-button type="submit">Simpan Produk</x-button>
                <x-button href="{{ route('produk.index') }}" variant="secondary">
                    Kembali
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection