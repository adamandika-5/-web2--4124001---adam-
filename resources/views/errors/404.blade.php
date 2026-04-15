@extends('layouts.app')

@section('title', '404 Not Found')

@section('content')
<div class="flex flex-col items-center justify-center py-20 text-center">

    <h1 class="text-6xl font-bold text-red-500">404</h1>

    <p class="text-xl mt-4">Halaman tidak ditemukan</p>

    <a href="/" class="mt-6 bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
        Kembali ke Home
    </a>

</div>
@endsection