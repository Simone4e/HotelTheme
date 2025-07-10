@props(['title', 'resources' => []])
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }} - {{ config('app.name') }}</title>
    @vite(['resources/js/app.js', 'resources/css/app.css', ...$resources])
    @livewireStyles
    @livewireScripts
</head>

<body class="w-full min-h-screen flex flex-col bg-gray-200">
    <header class="relative top-0 left-0 right-0 z-20 bg-primary bg-opacity-40">
        <x-layouts.navbar />
    </header>
    <main class="grow flex flex-col">
        <section
            class="px-10 py-4 border-b-2 border-primary/25 bg-white shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3 relative">
            <h1 class="text-4xl font-bold text-gray-800">{{ $title }}</h1>
            <div class="flex gap-2 items-center">
                {{ $btns ?? '' }}
            </div>
            @if (session('success'))
                <x-toast type="success" :message="session('success')" />
            @endif
        </section>
        {{ $slot }}
    </main>
    <footer class="flex flex-col items-center justify-center border-t-2 border-primary/25 bg-white shadow-sm py-2">
        <div>Hotel Theme - Str. Fake 123, Italy</div>
        <div>info@hoteltheme.fake - 0123 456789</div>
        <div class="mt-3"> © {{ date('Y') }} Hotel Laravel. All rights reserved.</div>
    </footer>
</body>

</html>
