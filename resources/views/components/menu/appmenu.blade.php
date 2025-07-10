<x-navlink href="{{ route('index') }}" :active="request()->routeIs('index')">Home</x-navlink>
<x-navlink href="/rooms" :active="request()->routeIs('rooms.*')">Rooms</x-navlink>
<x-navlink href="/gallery" :active="request()->routeIs('gallery')">Gallery</x-navlink>
<x-navlink href="{{ route('contact') }}" :active="request()->routeIs('contact')">Contact</x-navlink>
