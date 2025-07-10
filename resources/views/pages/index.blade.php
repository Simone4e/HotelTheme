<x-layouts.app title="Homepage" :resources="['resources/js/homepage.js']">

    <!-- HERO SECTION -->
    <section class="relative h-[600px] lg:h-[800px] xl:h-[1000px]">
        <img class="absolute inset-0 w-full h-full object-cover" src="{{ asset('/storage/rooms/room1.jpg') }}"
            alt="Room Image" />
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-transparent"></div>

        <div class="relative z-10 flex flex-col items-center justify-center h-full text-center px-4">
            <h1 x-data="{ shown: false }" x-intersect:enter.threshold.3="setTimeout(() => shown = true, 300)"
                :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                class="transition-all duration-700 ease-out text-white font-serif text-5xl lg:text-7xl font-bold tracking-wide">
                Welcome to Our Luxury Hotel
            </h1>
            <p x-data="{ shown: false }" x-intersect:enter.threshold.3="setTimeout(() => shown = true, 500)"
                :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                class="transition-all duration-700 ease-out text-white text-lg lg:text-2xl mt-4 max-w-2xl">
                Comfort, elegance and relaxation in the heart of the city
            </p>
            <x-a x-data="{ shown: false }" x-intersect:enter.threshold.3="setTimeout(() => shown = true, 700)"
                :class="shown ? 'opacity-100 scale-100' : 'opacity-0 scale-90'"
                class="mt-6 px-6 py-3 bg-primary text-gray-900 rounded-full shadow-md transition-all duration-700 ease-out transform hover:scale-105"
                href="{{ route('rooms.index') }}">
                Book now
            </x-a>
        </div>
    </section>

    <!-- SEARCH FORM -->
    <div x-data="{ shown: false }" x-intersect:enter.threshold.3="setTimeout(() => shown = true, 300)"
        :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
        class="max-w-4xl mx-auto -mt-14 z-20 relative transition-all duration-700 ease-out">
        <div class="bg-white/90 backdrop-blur-md shadow-2xl rounded-xl p-6">
            <form class="flex flex-col sm:flex-row items-center gap-4" method="GET"
                action="{{ route('rooms.index') }}">
                <div class="flex-1 w-full">
                    <x-inputs.input id="dateFromTo" name="dateFromTo" label="Date from - to"
                        placeholder="Select date range" />
                </div>
                <div>
                    <x-btn type="submit"
                        class="w-full sm:w-auto px-6 py-3 rounded-full shadow-md bg-primary text-white hover:bg-primary-dark transition">
                        Search
                    </x-btn>
                </div>
            </form>
        </div>
    </div>

    <!-- ABOUT / WELCOME SECTION -->
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4 grid md:grid-cols-2 gap-10 items-center">
            <div x-data="{ shown: false }" x-intersect:enter.threshold.3="setTimeout(() => shown = true, 300)"
                :class="shown ? 'opacity-100 -translate-x-0' : 'opacity-0 -translate-x-8'"
                class="transition-all duration-700 ease-out space-y-6">
                <h2 class="text-4xl lg:text-5xl font-serif font-bold">Experience True Luxury</h2>
                <p class="text-gray-600 text-lg leading-relaxed">
                    Welcome to our boutique hotel in the heart of the city, where sophistication meets comfort. Enjoy
                    personalized service, refined interiors, and an unforgettable stay designed to delight every guest.
                </p>
                <x-a class="inline-block mt-4 px-6 py-3 rounded-full bg-primary text-white hover:bg-primary-dark shadow-md transition-all duration-300 ease-in-out transform hover:scale-105"
                    href="{{ route('rooms.index') }}">
                    Discover Our Rooms
                </x-a>
            </div>
            <div x-data="{ shown: false }" x-intersect:enter.threshold.3="setTimeout(() => shown = true, 500)"
                :class="shown ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-8'"
                class="transition-all duration-700 ease-out">
                <img src="{{ asset('/storage/rooms/room2.jpg') }}" alt="Luxury Room"
                    class="rounded-xl shadow-lg object-cover w-full h-96">
            </div>
        </div>
    </section>

    <!-- WHY CHOOSE US -->
    <section class="py-16 bg-gray-200">
        <div class="max-w-6xl mx-auto px-4">
            <h2 x-data="{ shown: false }" x-intersect:enter.threshold.3="setTimeout(() => shown = true, 300)"
                :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                class="transition-all duration-700 ease-out text-3xl lg:text-4xl font-serif text-center font-bold mb-12">
                Why Choose Us
            </h2>
            <div class="grid gap-8 md:grid-cols-3 text-center">
                <div x-data="{ shown: false }" x-intersect:enter.threshold.3="setTimeout(() => shown = true, 300)"
                    :class="shown ? 'opacity-100 scale-100' : 'opacity-0 scale-90'"
                    class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all duration-700 ease-out">
                    <svg class="mx-auto h-12 w-12 text-primary mb-4" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8c-1.657 0-3-1.343-3-3V3m0 18v-2a3 3 0 013-3h6a3 3 0 013 3v2m-9-2h6m-6 0V3" />
                    </svg>
                    <h3 class="text-xl font-semibold mb-2">Exceptional Service</h3>
                    <p class="text-gray-600">Our dedicated team is ready 24/7 to make your stay truly memorable.</p>

                </div>
                <div x-data="{ shown: false }" x-intersect:enter.threshold.3="setTimeout(() => shown = true, 500)"
                    :class="shown ? 'opacity-100 scale-100' : 'opacity-0 scale-90'"
                    class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all duration-700 ease-out">
                    <svg class="mx-auto h-12 w-12 text-primary mb-4" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18m-9 9V3" />
                    </svg>
                    <h3 class="text-xl font-semibold mb-2">Prime Location</h3>
                    <p class="text-gray-600">In the heart of the city, close to all attractions and cultural landmarks.
                    </p>
                </div>
                <div x-data="{ shown: false }" x-intersect:enter.threshold.3="setTimeout(() => shown = true, 700)"
                    :class="shown ? 'opacity-100 scale-100' : 'opacity-0 scale-90'"
                    class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all duration-700 ease-out">
                    <svg class="mx-auto h-12 w-12 text-primary mb-4" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <h3 class="text-xl font-semibold mb-2">Elegant Design</h3>
                    <p class="text-gray-600">Modern yet classic interiors offering comfort and luxury in every detail.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- AMENITIES -->
    <section x-data="{ shown: false }" x-intersect:enter.threshold.3="setTimeout(() => shown = true, 300)"
        :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
        class="bg-white py-16 transition-all duration-700 ease-out">
        <div class="max-w-5xl mx-auto px-4">
            <h2 class="text-3xl lg:text-4xl font-serif text-center font-bold mb-10">Our Amenities</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 text-center">
                <div class="space-y-3">
                    <x-listbenefit>Central position</x-listbenefit>
                    <x-listbenefit>Breakfast included</x-listbenefit>
                    <x-listbenefit>Reception 24/7</x-listbenefit>
                </div>
                <div class="space-y-3">
                    <x-listbenefit>Air-conditioned rooms</x-listbenefit>
                    <x-listbenefit>Wi-Fi free</x-listbenefit>
                    <x-listbenefit>Free parking</x-listbenefit>
                </div>
                <div class="space-y-3">
                    <x-listbenefit>Room service</x-listbenefit>
                    <x-listbenefit>Spa & Wellness</x-listbenefit>
                    <x-listbenefit>Pet friendly</x-listbenefit>
                </div>
            </div>
        </div>
    </section>

</x-layouts.app>
