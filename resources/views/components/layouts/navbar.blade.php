<!-- WRAPPER CON x-data -->
<div x-data="{ open: false }">
    <!-- NAV BAR -->
    <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
        <div class="flex lg:flex-1">
            <a href="#" class="-m-1.5 p-1.5">
                <span class="sr-only">Hotel Theme</span>
                <div class="text-4xl font-bold text-white">Hotel Theme</div>
            </a>
        </div>

        <!-- Bottone apri menu -->
        <div class="flex lg:hidden">
            <button type="button" @click="open = true"
                class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-white">
                <span class="sr-only">Open main menu</span>
                <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>

        <!-- Menu desktop -->
        <div class="hidden lg:flex lg:flex-1 lg:gap-x-12 lg:justify-end">
            @if (request()->is('admin/*'))
                <x-menu.adminmenu />
            @else
                <x-menu.appmenu />
            @endif
        </div>
    </nav>

    <!-- MENU MOBILE -->
    <div x-show="open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-full opacity-0" x-cloak
        class="fixed inset-y-0 right-0 z-50 w-full max-w-sm overflow-y-auto bg-primary p-6 transform lg:hidden"
        role="dialog" aria-modal="true">

        <!-- Sfondo cliccabile -->
        <div class="fixed inset-0 bg-black/30" @click="open = false"></div>

        <div class="relative z-50">
            <div class="flex items-center justify-between">
                <a href="#" class="-m-1.5 p-1.5">
                    <span class="sr-only">Hotel theme</span>
                    <div class="text-4xl font-bold text-white">Hotel Theme</div>
                </a>
                <!-- Bottone chiudi -->
                <button type="button" @click="open = false" class="-m-2.5 rounded-md p-2.5 text-white">
                    <span class="sr-only">Close menu</span>
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Menu contenuto -->
            <div class="mt-6 flow-root">
                <div class="-my-6 divide-y divide-gray-500/10">
                    <div class="space-y-2 py-6">
                        @if (request()->is('admin/*'))
                            <x-menu.adminmenu />
                        @else
                            <x-menu.appmenu />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
