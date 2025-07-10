@props(['value' => '', 'label' => null, 'options' => []])

@if ($label)
    <x-inputs.label for="{{ $attributes->get('id') }}">{{ $label }}</x-inputs.label>
@endif

<select
    {{ $attributes->merge([
        'class' =>
            'border-gray-300 hover:border-primary focus:border-primary focus:ring-primary focus:outline-primary border-2 rounded-lg block w-full p-2.5',
    ]) }}>

    @foreach ($options as $key => $option)
        <option value="{{ $key }}" {{ $key === $value ? 'selected' : '' }}>{{ $option }}</option>
    @endforeach
</select>
