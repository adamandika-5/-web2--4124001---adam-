@extends('layouts.app')

@section('title', 'Produk')

@section('content')

<h1 class="text-2xl font-bold mb-6">Daftar Produk</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    @foreach($produk as $item)
    <div class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition">

        <img src="{{ $item['gambar'] }}" class="w-full rounded mb-3">

        <h2 class="text-lg font-semibold">{{ $item['nama'] }}</h2>

        <p class="text-gray-600 text-sm">
            {{ $item['deskripsi'] }}
        </p>

        <a href="/produk/{{ $item['id'] }}"
           class="inline-block mt-3 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Detail
        </a>

    </div>
    @endforeach

</div>

@endsection