@props([
    'type' => 'info',
    'message' => null,
    'dismissible' => false,
])

@php
    $classes = match ($type) {
        'success' => 'bg-green-100 border-green-400 text-green-800',
        'error' => 'bg-red-100 border-red-400 text-red-800',
        'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-800',
        default => 'bg-blue-100 border-blue-400 text-blue-800',
    };
@endphp

@if ($message || trim($slot))
    <div data-alert {{ $attributes->merge(['class' => "mb-4 rounded-lg border px-4 py-3 {$classes}"]) }}>
        <div class="flex items-start justify-between gap-3">
            <div class="text-sm">
                {{ $message ?: $slot }}
            </div>

            @if ($dismissible)
                <button
                    type="button"
                    class="shrink-0 text-lg font-bold leading-none opacity-70 hover:opacity-100"
                    onclick="this.closest('[data-alert]').remove()"
                >
                    ×
                </button>
            @endif
        </div>
    </div>
@endif