@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto py-10">

    <img src="{{ $produk['gambar'] }}"
         class="w-full h-80 object-cover rounded-xl mb-6">

    <h1 class="text-3xl font-bold mb-3">
        {{ $produk['nama'] }}
    </h1>

    <p class="text-gray-600 mb-4">
        {{ $produk['deskripsi'] }}
    </p>

    <p class="text-2xl font-bold text-teal-600 mb-3">
        Rp {{ number_format($produk['harga'], 0, ',', '.') }}
    </p>

    <p class="text-gray-500">
        Stok: {{ $produk['stok'] }}
    </p>

</div>

@endsection