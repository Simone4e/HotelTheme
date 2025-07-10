<x-layouts.app title="Contact" :resources="['resources/js/contact.js']">
    <x-layouts.title-section title="Contact" link="/storage/contact.jpg" />
    <section>
        <div class="px-5 mt-5 grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <div class="space-y-10">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Contact us</h2>
                        <p class="mt-1 text-sm text-gray-500">Get in touch with us for any questions or concerns.</p>
                    </div>
                    <form method="POST" action="{{ route('contact.send') }}">
                        @csrf
                        @honeypot
                        <div class="grid grip-cols-1 md:grid-cols-6 gap-6">

                            <div class="col-span-6 md:col-span-3">
                                <x-inputs.input label="Name" name="name" id="name" value="{{ old('name') }}"
                                    required />
                            </div>

                            <div class="col-span-6 md:col-span-3">
                                <x-inputs.input type="email" label="Email" name="email" id="email"
                                    value="{{ old('email') }}" required />
                            </div>

                            <div class="col-span-6 md:col-span-3">
                                <x-inputs.input label="Phone" name="phone" id="phone" value="{{ old('phone') }}"
                                    required />
                            </div>

                            <div class="col-span-6">
                                <x-inputs.textarea name="message" label="Message"
                                    id="message">{{ old('message') }}</x-inputs.textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <x-btn type="submit">Send</x-btn>
                        </div>
                    </form>
                </div>
            </div>
            <div id="map" class="h-[500px] md:h-full rounded-xl shadow-sm border border-gray-200"></div>
        </div>
    </section>
</x-layouts.app>
