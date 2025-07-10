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

<body class="w-full min-h-screen flex flex-col bg-gray-200 font-sans text-gray-800">
    <header class="relative top-0 left-0 right-0 z-20 bg-primary bg-opacity-40">
        <x-layouts.navbar />
    </header>
    <main class="grow flex flex-col">
        @if (session('success'))
            <x-toast type="success" :message="session('success')" />
        @endif
        {{ $slot }}
    </main>
    <footer class="flex flex-col items-center justify-center bg-gray-200 shadow-sm py-2">
        <div>Hotel Theme - Str. Fake 123, Italy</div>
        <div>info@hoteltheme.fake - 0123 456789</div>
        <div class="mt-3"> © {{ date('Y') }} Hotel Laravel. All rights reserved.</div>
    </footer>

    @section('scripts')
    </body>

    </html>
