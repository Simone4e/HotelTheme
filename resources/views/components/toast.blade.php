@props(['type' => 'success', 'message'])

<div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100);
setTimeout(() => show = false, 4000);" x-show="show" x-cloak
    x-transition:enter="transition transform ease-out duration-500" x-transition:enter-start="translate-x-full opacity-0"
    x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave="transition transform ease-in duration-500"
    x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
    class="fixed right-5 md:right-70 z-50 px-6 py-3 rounded shadow-xl/30 text-white bg-primary">
    {{ $message }}
</div>
