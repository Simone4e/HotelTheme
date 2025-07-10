<x-layouts.app title="{{ $room->name }}">
    <x-layouts.title-section title="{{ $room->name }}" link="storage/{{ $room->preview }}" />
    <section class="px-5 py-10 w-full">
        <div class="flex flex-col lg:flex-row gap-10 justify-between mx-10">
            <div class="flex-1 space-y-6">
                <div class="flex gap-6 text-gray-600 text-2xl">
                    <div class="flex items-center gap-1">
                        <x-icons.bed class="w-5 h-5 text-primary" /> {{ $room->beds }} Beds
                    </div>
                    <div class="flex items-center gap-1">
                        <x-icons.user class="w-5 h-5 text-primary" /> {{ $room->peoples }} People
                    </div>
                    <div class="flex items-center gap-1">
                        <x-icons.ruler class="w-5 h-5 text-primary" /> {{ $room->meters }} m²
                    </div>
                </div>

                <div class="text-2xl font-bold text-primary">
                    €{{ number_format($room->price, 2, ',', '.') }} <span class="text-sm font-medium text-gray-500">/
                        night</span>
                </div>

                <p class="text-gray-700 leading-relaxed text-2xl">
                    {{ $room->description }}
                </p>


                <!-- Reservation Modal -->
                <div x-data="reservationModal({
                    oldCheckin: '{{ old('date_checkin') }}',
                    oldCheckout: '{{ old('date_checkout') }}'
                })" x-init="init()" x-cloak>
                    <a href="#" @click.prevent="openReservation = true"
                        class="inline-block px-6 py-3 rounded-lg bg-primary text-white font-semibold hover:bg-primary/90 transition">
                        Prenota ora
                    </a>
                    <div x-show="openReservation"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">

                        <div class="absolute inset-0" @click="openReservation = false"></div>
                        <div
                            class="relative z-10 bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 overflow-y-auto max-h-[90vh]">

                            <button @click="openReservation = false"
                                class="absolute top-4 right-4 text-gray-500 hover:text-primary text-2xl">&times;</button>

                            <h2 class="text-2xl font-bold mb-4 text-primary">Book your stay in {{ $room->name }}</h2>

                            <form method="POST" action="{{ route('reservations.store', $room) }}">
                                @csrf
                                @honeypot
                                <input type="hidden" name="room_id" value="{{ $room->id }}">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-inputs.input label="Name" name="name_client"
                                            value="{{ old('name_client') }}" required />
                                    </div>
                                    <div>
                                        <x-inputs.input label="Email" name="email" type="email"
                                            value="{{ old('email') }}" required />
                                    </div>
                                    <div>
                                        <x-inputs.input label="Phone" name="phone" value="{{ old('phone') }}" />
                                    </div>
                                    <div>
                                        <x-inputs.input x-ref="daterange" readonly label="Dates" required />
                                        <input type="hidden" name="date_checkin" value="{{ old('date_checkin') }}"
                                            x-ref="checkin">
                                        <input type="hidden" name="date_checkout" value="{{ old('date_checkout') }}"
                                            x-ref="checkout">
                                    </div>
                                    <div class="md:col-span-2">
                                        <x-inputs.textarea label="Messages" name="messages"
                                            required>{{ old('messages') }}</x-inputs.textarea>
                                    </div>
                                </div>

                                <div class="mt-4 flex justify-end gap-3">
                                    <x-btn type="button" @click="openReservation = false">Cancel</x-btn>
                                    <x-btn type="submit">Send Reservation</x-btn>
                                </div>
                                @if ($errors->any())
                                    <div class="mb-4 mt-4">
                                        <div class="rounded-md bg-red-50 p-4">
                                            <h3 class="text-sm font-medium text-red-800">There were some problems with
                                                your input.</h3>
                                            <ul class="mt-2 list-disc pl-5 text-sm text-red-600 space-y-1">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>


            </div>

            <div class="lg:w-1/3">
                <x-image.room-gallery :images="$room->allImages()" :alt="$room->name" size="w-full h-64 rounded-xl" autoplay />
            </div>
        </div>
    </section>

    {{-- Sezione: Altre camere --}}
    <section class="bg-gray-100 py-10">
        <div class="max-w-6xl mx-auto px-5">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Others rooms</h2>
            <div class="flex flex-col justify-center items-center space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach ($otherRooms as $other)
                        <x-room-card :room="$other" />
                    @endforeach
                </div>
                <div><x-a href="{{ route('rooms.index') }}">Look others</x-a></div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('reservationModalOpen', {{ session('openReservation', false) ? 'true' : 'false' }});
        });

        function reservationModal({
            oldCheckin = '',
            oldCheckout = ''
        }) {
            return {
                openReservation: false,
                flatpickrInstance: null,
                oldCheckin,
                oldCheckout,

                init() {
                    this.openReservation = Alpine.store('reservationModalOpen');
                    this.flatpickrInstance = flatpickr(this.$refs.daterange, {
                        mode: 'range',
                        altInput: true,
                        altFormat: 'd/m/Y',
                        dateFormat: 'Y-m-d',
                        minDate: 'today',
                        onChange: (selectedDates, dateStr, instance) => {
                            if (selectedDates.length === 2) {
                                const [start, end] = selectedDates;
                                this.$refs.checkin.value = instance.formatDate(start, 'Y-m-d');
                                this.$refs.checkout.value = instance.formatDate(end, 'Y-m-d');
                            }
                        },
                        onReady: (selectedDates, dateStr, instance) => {
                            if (this.oldCheckin && this.oldCheckout) {
                                instance.setDate([this.oldCheckin, this.oldCheckout]);
                                this.$refs.checkin.value = this.oldCheckin;
                                this.$refs.checkout.value = this.oldCheckout;
                            }
                        },
                        onOpen: async () => {
                            try {
                                const response = await fetch(`/api/rooms/{{ $room->id }}/booked`);
                                const data = await response.json();
                                const dateDisabled = data.map(el => ({
                                    from: el[0],
                                    to: el[1]
                                }));
                                this.flatpickrInstance.set('disable', dateDisabled);

                                let today = new Date();
                                let nextValidDate = null;
                                for (let i = 0; i < 365; i++) {
                                    let candidate = new Date(today);
                                    candidate.setDate(candidate.getDate() + i);

                                    if (!this.flatpickrInstance.isEnabled(candidate)) {
                                        continue;
                                    }

                                    nextValidDate = candidate;
                                    break;
                                }

                                if (nextValidDate) {
                                    this.flatpickrInstance.jumpToDate(nextValidDate);
                                }
                            } catch (e) {
                                console.error(e);
                            }
                        }

                    });
                }
            }
        }
    </script>

</x-layouts.app>
