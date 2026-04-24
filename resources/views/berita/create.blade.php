@extends('layouts.app')

@section('title', 'Tambah Berita')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    @if ($errors->any())
        <x-alert type="error" dismissible>
            Periksa kembali form berita yang diisi.
        </x-alert>
    @endif

    <x-card title="Form Tambah Berita Toko Bangunan">
        <form action="{{ route('berita.store') }}" method="POST" class="space-y-5">
            @csrf

            <x-input
                label="Judul Berita"
                name="judul"
                required
                placeholder="Contoh: Promo Semen Akhir Bulan"
            />

            <x-input
                label="Kategori"
                name="kategori"
                required
                placeholder="Contoh: Promo, Tips, Informasi"
            />

            <x-input
                label="Penulis"
                name="penulis"
                required
                placeholder="Contoh: Admin Sinar Alam"
            />

            <div class="space-y-1">
                <label for="isi" class="block text-sm font-medium text-slate-700">
                    Isi Berita
                </label>
                <textarea
                    id="isi"
                    name="isi"
                    rows="5"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500"
                    placeholder="Tulis isi berita di sini..."
                >{{ old('isi') }}</textarea>
                @error('isi')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <x-button type="submit">Simpan Berita</x-button>
                <x-button href="{{ route('berita.index') }}" variant="secondary">
                    Kembali
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection