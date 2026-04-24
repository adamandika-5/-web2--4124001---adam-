@props([
    'label' => null,
    'name',
    'type' => 'text',
    'required' => false,
    'hint' => null,
    'placeholder' => '',
])

@php
    $inputId = $attributes->get('id', $name);
    $hasError = $errors->has($name);
    $value = old($name, $attributes->get('value'));

    $classes = 'w-full rounded-lg border px-4 py-2 focus:outline-none focus:ring-2 ';
    $classes .= $hasError
        ? 'border-red-500 focus:ring-red-300'
        : 'border-gray-300 focus:border-teal-500 focus:ring-teal-500';
@endphp

<div class="space-y-1">
    @if ($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-slate-700">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <input
        id="{{ $inputId }}"
        name="{{ $name }}"
        type="{{ $type }}"
        placeholder="{{ $placeholder }}"
        @required($required)
        @if (!in_array($type, ['password', 'file'])) value="{{ $value }}" @endif
        {{ $attributes->except(['id', 'value'])->merge(['class' => $classes]) }}
    >

    @if ($hint)
        <p class="text-xs text-slate-500">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>