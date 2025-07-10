@props(['active' => false])

@php
    $class = $active
        ? '-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900  bg-gray-50'
        : '-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-white  hover:bg-gray-400/50  bg-none';
@endphp


<a {{ $attributes->merge(['class' => $class]) }} aria-current="{{ $active ? 'page' : 'false' }}"
    class="{{ $class }}">{{ $slot }}</a>
