@extends('layouts.app')

@section('title', 'Berita Toko Bangunan')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    @if (session('success'))
        <x-alert type="success" :message="session('success')" dismissible />
    @endif

    @if (session('info'))
        <x-alert type="info" :message="session('info')" dismissible />
    @endif

    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Berita Toko Bangunan</h1>
            <p class="text-slate-600">Info promo, tips konstruksi, dan update material terbaru.</p>
        </div>

        <x-button href="{{ route('berita.create') }}">
            + Tulis Berita
        </x-button>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($berita as $item)
            <x-card>
                <img src="{{ $item['gambar'] }}" alt="{{ $item['judul'] }}" class="h-48 w-full rounded-lg object-cover">

                <div class="mt-4 flex items-center justify-between">
                    <x-badge :color="$item['badge']">
                        {{ $item['kategori'] }}
                    </x-badge>
                    <span class="text-xs text-slate-500">{{ $item['tanggal'] }}</span>
                </div>

                <h3 class="mt-3 text-xl font-semibold text-slate-800">{{ $item['judul'] }}</h3>
                <p class="mt-2 text-sm text-slate-600">{{ $item['ringkasan'] }}</p>

                <div class="mt-4">
                    <x-button href="#" variant="secondary" size="sm">
                        Baca Selengkapnya
                    </x-button>
                </div>
            </x-card>
        @empty
            <div class="lg:col-span-3">
                <x-alert type="warning">
                    Belum ada berita yang dipublikasikan.
                </x-alert>
            </div>
        @endforelse
    </div>
</div>
@endsection