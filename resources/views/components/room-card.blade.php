@props(['room'])
<a href="{{ route('rooms.show', $room) }}"
    class="group bg-white overflow-hidden rounded-xl shadow-md hover:shadow-2xl
           transform transition duration-300 hover:scale-[1.02] flex flex-col mb-2">

    <div class="h-48 overflow-hidden shadow-lg shadow-primary-300">
        <img src="{{ asset('storage/' . $room->preview) }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            alt="{{ $room->name }}">
    </div>


    <div class="p-4 flex flex-col grow">


        <h3 class="text-lg font-semibold tracking-tight text-gray-800 truncate">
            {{ $room->name }}
        </h3>


        <div class="mt-2 flex flex-wrap gap-4 text-sm text-gray-600">
            <div class="flex items-center gap-1">
                <x-icons.bed class="text-primary" /> {{ $room->beds }}
            </div>
            <div class="flex items-center gap-1">
                <x-icons.user class="text-primary" /> {{ $room->peoples }}
            </div>
            <div class="flex items-center gap-1">
                <x-icons.ruler class="text-primary" /> {{ $room->meters }} m²
            </div>
        </div>


        <p class="mt-2 text-sm text-gray-500 line-clamp-3">
            {{ $room->description }}
        </p>


        <div class="mt-auto text-right py-4">
            <span class="inline-block bg-primary/10 text-primary font-bold text-xl px-3 py-1 rounded-lg">
                €{{ number_format($room->price, 2, ',', '.') }}
            </span>
        </div>
    </div>
</a>
