@props(['options', 'reservationId', 'selected'])

@php
    $selectedLabel = $options[$selected] ?? $selected;
@endphp

<div x-data="{ open: false, value: @js($selected) }" class="relative inline-block text-left w-36">
    {{-- Trigger --}}
    <button @click="open = !open" type="button"
        class="inline-flex items-center justify-between w-full rounded-md border border-gray-300 bg-white px-3 py-1 text-sm font-medium shadow-sm hover:bg-gray-50 focus:outline-none"
        aria-haspopup="true">
        {!! $selectedLabel !!}
        <svg class="ml-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.23 8.27a.75.75 0 01.02-1.06z"
                clip-rule="evenodd" />
        </svg>
    </button>

    {{-- Dropdown --}}
    <div x-show="open" @click.away="open = false" x-transition
        class="relative top-full left-0 mt-1 w-full z-50 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
        <ul class="py-1 text-sm">
            @foreach ($options as $value => $label)
                <li>
                    <button
                        @click="open = false; value = '{{ $value }}'; $wire.changeStatus('{{ $value }}', {{ $reservationId }})"
                        type="button" class="w-full text-left px-4 py-2 hover:bg-gray-100 cursor-pointer">
                        {!! $label !!}
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

</div>
