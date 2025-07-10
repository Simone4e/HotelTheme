<?php

namespace App\Livewire\Admin;

use App\Models\Room;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class RoomForm extends Component
{
    use WithFileUploads;

    public ?Room $room = null;

    public $name, $description, $price, $peoples, $beds, $meters, $actived;

    public $preview;
    public $existingPreview;


    public $gallery = [];
    public $existingGallery = [];
    public $removedGallery = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'peoples' => 'required|integer',
            'beds' => 'required|integer',
            'meters' => 'required|numeric',
            'actived' => 'boolean',
            'preview' => 'nullable|image|max:2048',
            'gallery.*' => 'image|max:2048',
        ];
    }

    public function mount($room = null)
    {
        $this->room = $room;

        if ($room) {
            $this->name = $room->name;
            $this->description = $room->description;
            $this->price = $room->price;
            $this->peoples = $room->peoples;
            $this->beds = $room->beds;
            $this->meters = $room->meters;
            $this->actived = $room->actived;

            $this->existingPreview = $room->preview ? asset('storage/' . $room->preview) : null;

            $this->existingGallery = $room->images->map(fn($img) => [
                'id' => $img->id,
                'url' => asset('storage/' . $img->path),
            ])->toArray();
        } else {
            $this->actived = true;
        }
    }

    public function addGalleryImage($file)
    {
        $this->validateOnly('gallery.*');
        $this->gallery[] = $file;
    }

    public function setPreviewImage($file)
    {
        $this->validateOnly('preview');
        $this->preview = $file;
    }

    public function removeExistingImage($id)
    {
        $this->removedGallery[] = $id;
        $this->existingGallery = array_filter($this->existingGallery, fn($img) => $img['id'] != $id);
    }

    public function removeUploadedImage($index)
    {
        unset($this->gallery[$index]);
        $this->gallery = array_values($this->gallery);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'peoples' => $this->peoples,
            'beds' => $this->beds,
            'meters' => $this->meters,
            'actived' => $this->actived ?? false,
        ];

        if ($this->room) {
            $this->room->update($data);
        } else {
            $this->room = Room::create($data);
        }

        if ($this->preview) {
            $path = $this->preview->store('rooms/' . $this->room->id . '/', 'public');
            $this->room->update(['preview' => $path]);
        }

        if (!empty($this->removedGallery)) {
            \App\Models\Image::whereIn('id', $this->removedGallery)->get()->each(function ($img) {
                Storage::disk('public')->delete($img->path);
                $img->delete();
            });
        }

        foreach ($this->gallery as $image) {
            $path = $image->store('rooms/' . $this->room->id . '/gallery', 'public');
            $this->room->images()->create(['path' => $path]);
        }

        session()->flash('success', 'Room saved.');
        return redirect()->route('admin.rooms.index');
    }

    public function render()
    {
        return view('livewire.admin.room-form');
    }
}
