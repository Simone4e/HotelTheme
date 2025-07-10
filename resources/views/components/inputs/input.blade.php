@props(['value' => '', 'label' => null])

@if ($label)
    <x-inputs.label for="{{ $attributes->get('id') }}">{{ $label }}</x-inputs.label>
@endif

<input
    {{ $attributes->merge([
        'class' =>
            'border-gray-300 hover:border-primary focus:border-primary focus:ring-primary focus:outline-primary border-2 rounded-lg block w-full p-2.5',
    ]) }}
    value="{{ $value }}">
