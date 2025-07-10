<x-layouts.admin title="Reservations">
    <x-slot:btns>
        <x-a href="{{ route('admin.reservations.create') }}">
            Create
        </x-a>
    </x-slot:btns>
    <div class="px-5 mt-5">
        <livewire:reservation-table />
    </div>
</x-layouts.admin>
