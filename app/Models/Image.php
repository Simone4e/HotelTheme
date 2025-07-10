<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    protected $fillable = ['path', 'descrizione', 'room_id'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    protected static function booted()
    {
        static::deleting(function (Image $image) {
            if ($image->path && $image->path !== '') {
                Storage::disk('public')->delete($image->preview);
            }
        });
    }
}
