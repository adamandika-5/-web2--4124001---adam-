@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
])

@php
    $variantClasses = match ($variant) {
        'secondary' => 'bg-slate-200 text-slate-800 hover:bg-slate-300 focus:ring-slate-400',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-400',
        default => 'bg-teal-600 text-white hover:bg-teal-700 focus:ring-teal-400',
    };

    $sizeClasses = match ($size) {
        'sm' => 'px-3 py-2 text-sm',
        'lg' => 'px-6 py-3 text-lg',
        default => 'px-4 py-2 text-sm',
    };

    $commonClasses = "inline-flex items-center justify-center rounded-lg font-semibold transition focus:outline-none focus:ring-2 focus:ring-offset-2 {$variantClasses} {$sizeClasses}";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $commonClasses]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $commonClasses]) }}>
        {{ $slot }}
    </button>
@endif