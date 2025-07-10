<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'price', 'beds', 'meters', 'preview', 'peoples', 'actived'];
    protected $casts = [
        'price' => 'float',
        'actived' => 'boolean',
    ];


    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function allImages()
    {
        $images = $this->images->pluck('path')->toArray();

        if ($this->preview) {
            array_unshift($images, $this->preview);
        }

        return $images;
    }

    public static function safeBulkDelete(array $ids): void
    {
        collect($ids)
            ->filter()
            ->map(fn($id) => Room::find($id))
            ->filter()
            ->each(fn($room) => $room->delete());
    }

    public static function safeDelete(int $id): void
    {
        $room = Room::find($id);

        if ($room) {
            $room->delete();
        }
    }

    protected static function booted()
    {

        static::deleting(function (Room $room) {
            $room->reservations()->delete();

            $room->images()->delete();

            if ($room->preview && $room->preview !== '/rooms/default.jpg') {
                Storage::disk('public')->delete($room->preview);
            }
        });
    }
}
