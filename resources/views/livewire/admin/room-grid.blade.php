<div class="px-4 sm:px-6 lg:px-8">

    <div class="md:sticky top-0 z-10 bg-white border-b py-4 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 px-5">
            <div>
                <div class="relative">
                    <x-inputs.input wire:model.live.debounce.300ms="search" label="Search"
                        placeholder="Search by name/price/beds/meters..." />
                    <div wire:loading wire:target="search" class="absolute right-2 top-8">
                        <svg class="w-4 h-4 animate-spin text-primary" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8h4z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div>
                <x-inputs.select wire:model.live="sortField" label="Sort by" :options="[
                    'name' => 'Name',
                    'price' => 'Price',
                    'beds' => 'Beds',
                    'meters' => 'Meters',
                    'peoples' => 'People',
                ]" />
            </div>

            <div>
                <x-inputs.select wire:model.live="sortDirection" label="Order" :options="[
                    'asc' => 'Ascending',
                    'desc' => 'Descending',
                ]" />
            </div>

            <div wire:ignore>
                <x-inputs.input id="dateFromTo" wire:model.defer="dateFromTo" :value="$dateFromTo" label="Date from - to"
                    placeholder="Select date range" />
            </div>

            <div>
                <x-inputs.input type="number" wire:model.live="peoples" placeholder="Number of people..."
                    label="Peoples" />
            </div>

        </div>
    </div>

    <div wire:loading.flex class="justify-center items-center py-6 text-primary flex gap-2 font-medium">
        <svg class="animate-spin w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8h4z" />
        </svg>
        Loading...
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
        @forelse ($rooms as $room)
            <x-room-card :room="$room" wire:key="room-{{ $room->id }}" />
        @empty
            <p class="col-span-full text-center text-gray-500">No rooms found.</p>
        @endforelse
    </div>

    @if ($hasMore)
        <div wire:loading.flex class="justify-center py-6 text-primary font-semibold">
            Loading more rooms...
        </div>

        <div x-data="{
            observe() {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            $wire.call('loadMore');
                        }
                    });
                }, { threshold: 1 });
                observer.observe(this.$refs.trigger);
            }
        }" x-init="observe">
            <div x-ref="trigger" class="h-10 w-full"></div>
        </div>
    @endif

</div>
@script
    <script>
        document.addEventListener('livewire:load', initFlatpickr);
        document.addEventListener('livewire:navigated', initFlatpickr);
        document.addEventListener('livewire:updated', initFlatpickr);

        let lastDateStr = null;

        function initFlatpickr() {
            const input = document.getElementById('dateFromTo');
            if (!input) return;

            if (input._flatpickr) {
                input._flatpickr.destroy();
            }
            if (input.value !== '')
                lastDateStr = input.value;

            flatpickr(input, {
                mode: 'range',
                altInput: true,
                altFormat: 'd/m/Y',
                dateFormat: 'Y-m-d',
                minDate: 'today',
                defaultDate: input.value,
                onClose: function(selectedDates, dateStr) {
                    if (selectedDates.length > 0) {
                        if (dateStr !== lastDateStr) {
                            Livewire.find('{{ $componentId }}').set('dateFromTo', dateStr);
                            lastDateStr = dateStr;
                        }
                        return;
                    }

                    if (input.value === '' && lastDateStr !== '') {
                        Livewire.find('{{ $componentId }}').set('dateFromTo', '');
                        lastDateStr = '';
                    }
                }
            });
        }
    </script>
@endscript
