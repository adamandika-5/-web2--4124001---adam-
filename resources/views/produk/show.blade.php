@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')

<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">

    <img src="{{ $item['gambar'] }}" class="w-full rounded mb-4">

    <h1 class="text-2xl font-bold mb-2">
        {{ $item['nama'] }}
    </h1>

    <p class="text-gray-700 mb-4">
        {{ $item['deskripsi'] }}
    </p>

    <a href="/produk" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
        Kembali
    </a>

</div>

@endsection