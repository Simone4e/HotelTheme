<x-layouts.app title="Gallery">
    <x-layouts.title-section title="Gallery" link="/storage/rooms/room.jpg" />
    <div class="w-full px-10 py-10 grid grid-cols-3 gap-3">
        @foreach ($images as $index => $image)
            <x-image.room-gallery size="w-full h-[500px] object-fit" :images="$images" :start-index="$index"
                :preview="$image" />
        @endforeach
    </div>
</x-layouts.app>
