<?php

namespace App\Models;

use App\ReservationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = ['name_client', 'email', 'phone', 'room_id', 'date_checkin', 'date_checkout', 'messages', 'status'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public static function safeBulkDelete(array $ids): void
    {
        collect($ids)
            ->filter()
            ->map(fn($id) => Reservation::find($id))
            ->filter()
            ->each(fn($reservation) => $reservation->delete());
    }

    public static function safeDelete(int $id): void
    {
        $reservation = Reservation::find($id);

        if ($reservation) {
            $reservation->delete();
        }
    }
}
