@extends('layouts.app')

@section('title', 'Produk Toko Bangunan')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    @if (session('success'))
        <x-alert type="success" :message="session('success')" dismissible />
    @endif

    @if (session('error'))
        <x-alert type="error" :message="session('error')" dismissible />
    @endif

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-800">Produk Toko Bangunan</h1>
        <p class="text-slate-600">Pilih material bangunan terbaik untuk kebutuhan proyek Anda.</p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($produk as $item)
            <x-card class="group">
                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <x-badge :color="$item['badge']">
                            {{ $item['kategori'] }}
                        </x-badge>
                        <span class="text-sm text-slate-500">Stok: {{ $item['stok'] }}</span>
                    </div>
                </x-slot:header>

                <img src="{{ $item['gambar'] }}" alt="{{ $item['nama'] }}" class="mb-4 h-48 w-full rounded-lg object-cover">

                <h3 class="text-xl font-semibold text-slate-800">{{ $item['nama'] }}</h3>
                <p class="mt-2 text-sm text-slate-600">{{ $item['deskripsi'] }}</p>
                <p class="mt-4 text-lg font-bold text-teal-600">
                    Rp {{ number_format($item['harga'], 0, ',', '.') }}
                </p>

                <div class="mt-5 flex gap-3">
                    <x-button href="#" size="sm">Detail</x-button>
                    <x-button href="#" variant="secondary" size="sm">Beli</x-button>
                </div>
            </x-card>
        @empty
            <div class="lg:col-span-3">
                <x-alert type="warning">
                    Belum ada produk yang tersedia.
                </x-alert>
            </div>
        @endforelse
    </div>
</div>
@endsection