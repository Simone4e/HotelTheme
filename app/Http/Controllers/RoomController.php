<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class RoomController extends Controller
{
    /**
     * Display the admin list of rooms.
     */
    public function index()
    {
        return view('pages.admin.rooms.index');
    }

    /**
     * Show the form for creating a new room.
     */
    public function create()
    {
        return view('pages.admin.rooms.create');
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit(Room $room)
    {
        return view('pages.admin.rooms.edit', compact('room'));
    }

    /**
     * Return the booked date ranges for a room.
     *
     * Used by the public site datepicker to disable unavailable ranges.
     *
     * @param  Room  $room
     * @param  Reservation|null  $reservation
     * @return JsonResponse
     */
    public function reservationRoom(Room $room, ?Reservation $reservation = null): JsonResponse
    {
        $cacheKey = "room:{$room->id}:booked_dates";

        $booked = cache()->remember($cacheKey, 60, function () use ($room, $reservation) {
            return $room->reservations()
                ->when(
                    $reservation,
                    fn($query) => $query->where('id', '!=', $reservation->id)
                )
                ->whereIn('status', ['pending', 'confirmed'])
                ->get()
                ->map(fn($r) => [
                    Carbon::parse($r->date_checkin)->format('Y-m-d'),
                    Carbon::parse($r->date_checkout)->format('Y-m-d'),
                ]);
        });

        return response()->json($booked);
    }
}
