@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto py-10">

    <h1 class="text-3xl font-bold mb-6">
        Edit Produk
    </h1>

    <form class="space-y-4">

        <input
            type="text"
            value="{{ $produk['nama'] }}"
            class="w-full border rounded-xl px-4 py-3"
        >

        <input
            type="number"
            value="{{ $produk['harga'] }}"
            class="w-full border rounded-xl px-4 py-3"
        >

        <button
            class="bg-teal-600 text-white px-6 py-3 rounded-xl">
            Simpan
        </button>

    </form>

</div>

@endsection