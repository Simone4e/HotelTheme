@props([
    'src' => null,
    'alt' => 'Preview',
    'size' => 'w-16 h-16',
])

@php
    $hasBinding = $attributes->has('x-bind:src');
@endphp

<div x-data="{ show: false, fullSrc: '' }">
    <img {{ $attributes->merge([
        'class' => "$size object-cover rounded cursor-zoom-in hover:brightness-90 transition",
        'alt' => $alt,
    ]) }}
        @click="fullSrc = $el.getAttribute('src'); console.log('ei'); show = true"
        @if (!$hasBinding) src="{{ $src }}" @endif>

    {{-- Modal zoom --}}
    <div x-show="show" x-transition @click.away="show = false" @keydown.escape.window="show = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70">
        <div @click.away="show = false" class="p-4">
            <img {{ $attributes->merge([
                'class' => 'max-w-2xl max-h-[90vh] rounded shadow-xl cursor-zoom-out',
                'alt' => $alt,
            ]) }}
                @click="show = false" @if (!$hasBinding) src="{{ $src }}" @endif>
        </div>
    </div>
</div>
