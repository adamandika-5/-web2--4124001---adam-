@props([
    'title' => null,
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-slate-200 bg-white shadow transition hover:shadow-lg']) }}>
    @isset($header)
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
            {{ $header }}
        </div>
    @endisset

    <div class="p-6">
        @if ($title)
            <h3 class="mb-3 text-lg font-bold text-slate-800">{{ $title }}</h3>
        @endif

        {{ $slot }}
    </div>
</div>