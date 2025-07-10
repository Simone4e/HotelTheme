 <x-layouts.admin title="Create Reservation">

     <div class="px-5 mt-5 flex justify-center">
         <div class="w-full max-w-4xl bg-white rounded-xl shadow-sm border border-gray-200 p-8">
             <form method="POST" action="{{ route('admin.reservations.store') }}" enctype="multipart/form-data">
                 @csrf
                 <div class="space-y-10">
                     {{-- Title --}}
                     <div>
                         <h2 class="text-xl font-semibold text-gray-800">Reservation Details</h2>
                         <p class="mt-1 text-sm text-gray-500">Enter general details of the reservation.</p>
                     </div>

                     {{-- Fields Grid --}}
                     <div class="grid grid-cols-1 md:grid-cols-6 gap-6">

                         <div class="col-span-6 md:col-span-3">
                             <x-inputs.input label="Name client" name="name_client" id="name_client"
                                 value="{{ old('name_client') }}" required />
                         </div>

                         <div class="col-span-6 md:col-span-3">
                             <x-inputs.input type="email" name="email" label="Email" id="email"
                                 value="{{ old('email') }}" />
                         </div>

                         <div class="col-span-6 md:col-span-3">
                             <x-inputs.input name="phone" label="Phone" id="phone" value="{{ old('phone') }}" />
                         </div>

                         <div class="col-span-6 md:col-span-3">
                             <x-inputs.select name="status" label="Status" :options="$statusOptions"
                                 value="{{ old('status') }}" />
                         </div>

                         <div x-data="reservationForm()" x-init="init()" class="col-span-6 grid grid-cols-1 gap-4">

                             {{-- Select Room --}}
                             <div>
                                 <x-inputs.label for="room_id">Room</x-inputs.label>
                                 <select name="room_id" id="room_id" x-model="roomId">
                                     <option value="">-- Select a room --</option>
                                     @foreach ($rooms as $key => $room)
                                         <option value="{{ $key }}"
                                             {{ $key === old('room_id') ? 'selected' : '' }}>{{ $room }}
                                         </option>
                                     @endforeach
                                 </select>
                             </div>

                             {{-- Check-in/out (hidden finché non selezioni la stanza) --}}
                             <div x-show="roomId" x-cloak>
                                 <label class="block text-sm text-gray-700 mb-1">Check-in - Check-out</label>
                                 <div class="flex items-center gap-2">
                                     {{-- Input visibile per la selezione range --}}
                                     <x-inputs.input id="date_range" class="form-input w-full"
                                         placeholder="Check-in - Check-out" x-ref="daterange" readonly />

                                     {{-- Hidden inputs per Laravel --}}
                                     <input type="hidden" name="date_checkin" id="date_checkin" x-ref="checkin"
                                         value="{{ old('date_checkin') }}">
                                     <input type="hidden" name="date_checkout" id="date_checkout" x-ref="checkout"
                                         value="{{ old('date_checkout') }}">
                                 </div>

                             </div>

                         </div>

                         <div class="col-span-6">
                             <x-inputs.textarea name="messages" label="Messages"
                                 id="messages">{{ old('messages') }}</x-inputs.textarea>
                         </div>
                     </div>

                     {{-- Buttons --}}
                     <div class="flex flex-col-reverse md:flex-row items-center justify-end gap-4">
                         <x-a href="{{ route('admin.reservations.index') }}">
                             Cancel
                         </x-a>
                         <x-btn type="submit">Create Reservation</x-btn>
                     </div>
                 </div>
             </form>

             {{-- Error block --}}
             @if ($errors->any())
                 <div class="mt-6">
                     <div class="rounded-md bg-red-50 p-4">
                         <h3 class="text-sm font-medium text-red-800">There were some problems with your input.</h3>
                         <ul class="mt-2 list-disc pl-5 text-sm text-red-600 space-y-1">
                             @foreach ($errors->all() as $error)
                                 <li>{{ $error }}</li>
                             @endforeach
                         </ul>
                     </div>
                 </div>
             @endif
         </div>
     </div>
     <script>
         function reservationForm() {
             return {
                 roomId: @js(old('room_id')),
                 flatpickrInstance: null,

                 init() {
                     // Inizializza il select solo se non già fatto
                     const selectEl = document.getElementById('room_id');
                     if (selectEl && !selectEl.tomselect) {
                         new TomSelect(selectEl, {
                             placeholder: 'Select a room',
                             onChange: (value) => {
                                 this.roomId = value;
                                 this.flatpickrInstance.setDate(['', '']);
                                 this.$refs.checkin.value = '';
                                 this.$refs.checkout.value = '';
                                 this.fetchBookedDates();
                             }
                         });
                     }

                     // Inizializza flatpickr disabilitato
                     this.flatpickrInstance = flatpickr(this.$refs.daterange, {
                         mode: 'range',
                         altInput: true,
                         altFormat: 'd/m/Y',
                         dateFormat: 'Y-m-d',
                         minDate: 'today',
                         disable: [],
                         onOpen: () => {
                             if (!this.roomId) this.flatpickrInstance.close();
                         },
                         onChange: (selectedDates, dateStr, instance) => {
                             if (selectedDates.length === 2) {
                                 const [start, end] = selectedDates;
                                 this.$refs.checkin.value = instance.formatDate(start, 'Y-m-d');
                                 this.$refs.checkout.value = instance.formatDate(end, 'Y-m-d');
                             }
                         }
                     });


                     if (this.roomId && selectEl.tomselect.getValue() !== this.roomId) {
                         selectEl.tomselect.setValue([this.roomId]);
                         this.fetchBookedDates();
                     }

                     const oldCheckin = @js(old('date_checkin'));
                     const oldCheckout = @js(old('date_checkout'));

                     if (oldCheckin && oldCheckout) {
                         this.flatpickrInstance.setDate([oldCheckin, oldCheckout]);
                         this.$refs.checkin.value = oldCheckin;
                         this.$refs.checkout.value = oldCheckout;
                     }

                 },

                 async fetchBookedDates() {
                     if (!this.roomId) return;

                     try {
                         const response = await fetch(`/api/rooms/${this.roomId}/booked`);
                         const data = await response.json();

                         const dateDisabled = data.map(el => {
                             return {
                                 from: el[0],
                                 to: el[1]
                             };
                         });
                         this.flatpickrInstance.set('disable', dateDisabled);
                     } catch (e) {
                         console.error(e);
                     }
                 }
             }
         }
     </script>


 </x-layouts.admin>
