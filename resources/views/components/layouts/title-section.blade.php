@props(['link', 'title'])
<div class="relative h-[400px]">
    <img class="absolute inset-0 w-full h-full object-cover" src="{{ asset($link) }}" alt="{{ $title }}" />

    <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-4">
        <h2 class="text-white font-bold text-4xl lg:text-6xl">{{ $title }}</h2>
    </div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-transparent"></div>
</div>
