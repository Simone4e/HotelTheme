@props([
    'images' => [],
    'alt' => 'Room image',
    'size' => 'w-20 h-20',
    'autoplay' => false,
    'startIndex' => 0,
    'preview' => null,
])

@php
    $preview = $preview ?? ($images[0] ?? null);
    $uid = uniqid('glider_'); // ID unico per istanza
    $c = count($images);
@endphp

<div x-data="{
    show: false,
    current: 0,
    images: @js($images),
    open(index) {
        this.current = index;
        this.show = true;
    }
}" class="relative">
    {{-- AUTOPLAY GALLERY --}}
    @if ($autoplay && $c)
        <div class="relative w-full">
            {{-- Glider container con altezza dinamica tramite aspect ratio --}}
            <div id="{{ $uid }}" class="glider relative w-full aspect-video overflow-hidden rounded">
                @foreach ($images as $image)
                    <div @click="show = true; current = {{ $loop->index }}" class="w-full h-full">
                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $alt }}"
                            class="w-full h-full object-cover rounded cursor-pointer" />
                    </div>
                @endforeach
            </div>

            {{-- Controls --}}
            @if ($c > 1)
                <x-image.btn-gallery direction="left" label="Previous image" class="prev" />
                <x-image.btn-gallery direction="right" label="Next image" class="next" />
                <div role="tablist" class="glider-dots mt-2 text-center"></div>
            @endif
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                new Glider(document.getElementById('{{ $uid }}'), {
                    slidesToShow: 1,
                    draggable: true,
                    scrollLock: true,
                    dots: '.glider-dots',
                    arrows: {
                        prev: '.prev',
                        next: '.next',
                    },
                    responsive: [{
                            breakpoint: 640,
                            settings: {
                                slidesToShow: 1
                            }
                        },
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: 1
                            }
                        }
                    ]
                });
            });
        </script>
    @else
        {{-- STATIC PREVIEW --}}
        @if ($preview)
            <img src="{{ asset('storage/' . $preview) }}" alt="{{ $alt }}"
                class="{{ $size }} object-cover rounded cursor-pointer hover:brightness-90 transition"
                @click="open({{ $startIndex }})" />
        @endif
    @endif

    {{-- FULLSCREEN MODAL --}}
    <div x-show="show" x-transition @keydown.escape.window="show = false"
        class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center text-white" x-cloak>

        <template x-if="images.length > 0">
            <div class="relative w-full h-full flex items-center justify-center" @click.self="show = false">
                @if ($c > 1)
                    <x-image.btn-gallery direction="left" label="Previous image"
                        @click="current = (current - 1 + images.length) % images.length" />
                @endif
                <div class="p-4 flex justify-center items-center">
                    <img :src="'/storage/' + images[current]" alt="{{ $alt }}"
                        class="w-full h-auto max-w-[90vw] max-h-[80vh] object-contain rounded shadow" />
                </div>

                @if ($c > 1)
                    <x-image.btn-gallery direction="right" label="Next image"
                        @click="current = (current + 1) % images.length" />
                @endif
            </div>
        </template>
    </div>
</div>
