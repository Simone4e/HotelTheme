<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - {{ config('app.name') }}</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @livewireStyles
    @livewireScripts
</head>

<body class="w-full min-h-screen flex flex-col bg-gray-200">
    <main class="flex grow items-center justify-center">
        <div class="bg-white w-full max-w-md p-8 rounded shadow-md">
            <h1 class="text-2xl font-bold mb-6 text-center text-black">Login</h1>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                @honeypot
                <div class="mb-4">
                    <x-inputs.input type="email" id="email" name="email" label="Email" required />
                </div>
                <div class="mb-6">
                    <x-inputs.input type="password" id="password" name="password" label="Password" required />
                </div>
                <x-btn type="submit">Enter</x-btn>
            </form>
            @if ($errors->any())
                <div class="mt-4">
                    <div class="rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">There were some problems with your
                                    input.</h3>
                                <ul role="list" class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>

    <footer class="flex flex-col items-center justify-center bg-gray-200 py-6 text-md text-gray-600 text-center">
        <div>Hotel Theme - Str. Fake 123, Italy</div>
        <div>info@hoteltheme.fake - 0123 456789</div>
        <div class="mt-3">© {{ date('Y') }} Hotel Laravel. All rights reserved.</div>
    </footer>
</body>

</html>
