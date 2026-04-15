@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<h1 class="text-2xl font-bold mb-6">Tambah Produk</h1>

<form method="POST" action="#" class="bg-white p-6 rounded shadow max-w-lg">
    @csrf

    <!-- Nama -->
    <div class="mb-4">
        <label class="block mb-1 font-medium">Nama Produk</label>
        <input type="text" name="nama" class="w-full border p-2 rounded">

        @error('nama')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <!-- Harga -->
    <div class="mb-4">
        <label class="block mb-1 font-medium">Harga</label>
        <input type="text" name="harga" class="w-full border p-2 rounded">

        @error('harga')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <!-- Deskripsi -->
    <div class="mb-4">
        <label class="block mb-1 font-medium">Deskripsi</label>
        <textarea name="deskripsi" class="w-full border p-2 rounded"></textarea>
    </div>

    <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
        Simpan
    </button>
</form>
@endsection