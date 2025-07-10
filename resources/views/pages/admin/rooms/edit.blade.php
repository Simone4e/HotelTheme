<x-layouts.admin title="Edit Room {{ $room->name }}">
    <div class="px-5 mt-5 flex justify-center">
        <div class="w-full max-w-4xl bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <livewire:admin.room-form :room="$room" />
        </div>
    </div>
</x-layouts.admin>
