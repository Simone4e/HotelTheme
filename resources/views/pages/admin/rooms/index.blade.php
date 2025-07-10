<x-layouts.admin title="Rooms">
    <x-slot:btns>
        <x-a href="{{ route('admin.rooms.create') }}">
            Create
        </x-a>
    </x-slot:btns>
    <div class="px-5 mt-5">
        <livewire:room-table />
    </div>
</x-layouts.admin>
