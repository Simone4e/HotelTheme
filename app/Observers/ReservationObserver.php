<?php

namespace App\Observers;

use App\Models\Reservation;
use Illuminate\Support\Facades\Cache;

class ReservationObserver
{
    /**
     * Handle the Reservation "create/update" event.
     */
    public function saved(Reservation $reservation): void
    {
        Cache::forget("room:{$reservation->room_id}:booked_dates");
    }

    /**
     * Handle the Reservation "deleted" event.
     */
    public function deleted(Reservation $reservation): void
    {
        Cache::forget("room:{$reservation->room_id}:booked_dates");
    }
}
