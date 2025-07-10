<div x-data="cropperModal()" x-init="init()" class="relative">
    <div class="space-y-10">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                {{ $room ? 'Edit Room' : 'Create Room' }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Enter general details of the room.
            </p>
        </div>

        <form wire:submit.prevent="save" class="space-y-10">

            <div class="grid grid-cols-6 gap-6">

                <div class="col-span-6 sm:col-span-4">
                    <x-inputs.input label="Name" wire:model.defer="name" required />
                </div>

                <div class="col-span-6">
                    <x-inputs.textarea label="Description" wire:model.defer="description" required
                        rows="4"></x-inputs.textarea>

                </div>

                <div class="col-span-6 sm:col-span-2">
                    <x-inputs.input label="Price" type="number" step="0.01" wire:model.defer="price" required />

                </div>

                <div class="col-span-6 sm:col-span-2">
                    <x-inputs.input label="Peoples (max)" type="number" wire:model.defer="peoples" required />

                </div>

                <div class="col-span-6 sm:col-span-2">
                    <x-inputs.input label="Beds" type="number" wire:model.defer="beds" required />

                </div>

                <div class="col-span-6 sm:col-span-2">
                    <x-inputs.input label="Meters" type="number" step="0.1" wire:model.defer="meters" required />

                </div>

                <div class="col-span-6 sm:col-span-2">
                    <x-inputs.label for="actived">Active</x-inputs.label>
                    <div class="mt-2 flex items-center gap-x-3">
                        <input type="checkbox" wire:model="actived" value="1"
                            {{ old('actived', $room->actived ?? true) ? 'checked' : '' }}
                            class="size-4 rounded border-gray-300 text-primary focus:ring-primary">
                        <label for="actived" class="text-sm text-gray-700">Enable room</label>
                    </div>
                </div>


                <div class="col-span-6">
                    <label class="block text-sm font-medium text-gray-700">Preview Image</label>
                    <div class="mt-2 flex gap-2 items-center">
                        @if ($preview)
                            <img src="{{ $preview->temporaryUrl() }}"
                                @click="openLightbox('{{ $preview->temporaryUrl() }}')"
                                class="w-48 h-32 object-cover rounded border cursor-pointer hover:brightness-90 transition">
                        @elseif ($existingPreview)
                            <img src="{{ $existingPreview }}" @click="openLightbox('{{ $existingPreview }}')"
                                class="w-48 h-32 object-cover rounded border cursor-pointer hover:brightness-90 transition">
                        @else
                            <span class="text-gray-400">No preview selected</span>
                        @endif
                    </div>
                    <x-btn type="button" @click="openForTarget('preview')">Add preview</x-btn>

                </div>

                <div class="col-span-6">
                    <label class="block text-sm font-medium text-gray-700">Gallery Images</label>

                    <div class="flex gap-2 flex-wrap mt-2">
                        @foreach ($existingGallery as $img)
                            <div class="relative">
                                <img src="{{ $img['url'] }}" @click="openLightbox('{{ $img['url'] }}')"
                                    class="w-32 h-32 object-cover rounded border cursor-pointer hover:brightness-90 transition">
                                <button type="button" wire:click="removeExistingImage({{ $img['id'] }})"
                                    class="absolute top-1 right-1 bg-white text-primary-600 rounded-full p-1 shadow cursor-pointer">✕</button>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex gap-2 flex-wrap mt-2">
                        @foreach ($gallery as $index => $img)
                            <div class="relative">
                                <img src="{{ $img->temporaryUrl() }}"
                                    class="w-32 h-32 object-cover rounded border cursor-pointer hover:brightness-90 transition"
                                    @click="openLightbox('{{ $img->temporaryUrl() }}')" />

                                <button type="button" wire:click="removeUploadedImage({{ $index }})"
                                    class="absolute top-1 right-1 bg-white text-primary-600 rounded-full p-1 shadow cursor-pointer">✕</button>
                            </div>
                        @endforeach
                    </div>

                    <input type="file" multiple accept="image/*" x-ref="galleryInput" class="hidden"
                        @change="handleMultipleGallery($event)">

                    <x-btn type="button" @click="$refs.galleryInput.click()">Add Gallery Images</x-btn>

                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-6">
                <a href="{{ route('admin.rooms.index') }}"
                    class="mt-4 bg-primary text-white uppercase rounded py-2 px-4 cursor-pointer">Cancel</a>
                <x-btn type="submit">Save</x-btn>
            </div>
        </form>

        <div x-show="show" x-cloak class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center">
            <div class="bg-white p-6 rounded shadow max-w-5xl w-full max-h-[90vh] overflow-auto">
                <div class="mb-4">
                    <img x-ref="image" class="w-full max-h-[70vh] object-contain mx-auto" alt="Crop target">
                </div>
                <div class="flex justify-end gap-2">
                    <x-btn type="button" @click="cancel()">Cancel</x-btn>
                    <x-btn type="button" @click="apply()">Crop</x-btn>
                </div>
            </div>
        </div>

        {{-- Lightbox Modal --}}
        <div x-show="lightboxOpen" x-cloak @click.self="lightboxOpen = false"
            class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center">
            <img :src="lightboxSrc" class="max-w-full max-h-[90vh] rounded shadow-lg">
            <button @click="lightboxOpen = false"
                class="absolute top-4 right-4 text-white text-3xl font-bold">&times;</button>
        </div>
    </div>
</div>

<script>
    function cropperModal() {
        return {
            show: false,
            cropper: null,
            imageEl: null,
            targetField: null,
            queue: [],
            currentFile: null,

            lightboxOpen: false,
            lightboxSrc: '',

            init() {},

            openForTarget(target) {
                this.targetField = target;
                this.chooseFile();
            },

            openLightbox(src) {
                this.lightboxSrc = src;
                this.lightboxOpen = true;
            },

            chooseFile() {
                const input = document.createElement('input');
                input.type = 'file';
                input.accept = 'image/*';
                input.onchange = e => {
                    if (e.target.files.length) {
                        this.loadFile(e.target.files[0]);
                    }
                };
                input.click();
            },

            handleMultipleGallery(e) {
                this.queue = Array.from(e.target.files);
                this.targetField = 'gallery';
                this.nextInQueue();
            },

            nextInQueue() {
                if (!this.queue.length) {
                    this.reset();
                    return;
                }
                this.currentFile = this.queue.shift();
                this.loadFile(this.currentFile);
            },

            loadFile(file) {
                this.show = true;
                this.$nextTick(() => {
                    this.imageEl = this.$refs.image;
                    this.imageEl.src = URL.createObjectURL(file);
                    if (this.cropper) this.cropper.destroy();
                    this.cropper = new Cropper(this.imageEl, {
                        aspectRatio: 16 / 9,
                        viewMode: 1,
                        autoCropArea: 1,
                        responsive: true,
                        minCropBoxWidth: 800,
                        minCropBoxHeight: 450
                    });
                });
            },

            apply() {
                if (!this.cropper) return;
                this.cropper.getCroppedCanvas({
                    width: 1600,
                    height: 900
                }).toBlob(blob => {
                    const file = new File([blob], 'cropped.jpg', {
                        type: blob.type
                    });
                    if (this.targetField === 'preview') {
                        this.$wire.upload('preview', file, () => this.reset());
                    } else if (this.targetField === 'gallery') {
                        const index = this.$wire.gallery.length;
                        this.$wire.upload(`gallery.${index}`, file, () => this.nextInQueue());
                    }
                });
            },

            cancel() {
                this.reset();
            },

            reset() {
                if (this.cropper) this.cropper.destroy();
                this.cropper = null;
                this.show = false;
                this.currentFile = null;
                this.queue = [];
            },
        }
    }
</script>
