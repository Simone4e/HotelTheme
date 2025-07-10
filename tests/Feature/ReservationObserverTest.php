<?php

use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


test('it clears cache on reservation creation', function () {
    $room = Room::factory()->create();

    Cache::put("room:{$room->id}:booked_dates", 'TEST', 600);
    expect(Cache::get("room:{$room->id}:booked_dates"))->toBe('TEST');

    Reservation::factory()->create(['room_id' => $room->id]);

    expect(Cache::get("room:{$room->id}:booked_dates"))->toBeNull();
});


test('it clears cache on reservation update', function () {
    $room = Room::factory()->create();
    $reservation = Reservation::factory()->create(['room_id' => $room->id]);

    Cache::put("room:{$room->id}:booked_dates", 'TEST', 600);
    expect(Cache::get("room:{$room->id}:booked_dates"))->toBe('TEST');

    $reservation->update(['messages' => 'Updated']);

    expect(Cache::get("room:{$room->id}:booked_dates"))->toBeNull();
});


test('it clears cache on reservation deletion', function () {
    $room = Room::factory()->create();
    $reservation = Reservation::factory()->create(['room_id' => $room->id]);

    Cache::put("room:{$room->id}:booked_dates", 'TEST', 600);
    expect(Cache::get("room:{$room->id}:booked_dates"))->toBe('TEST');

    $reservation->delete();

    expect(Cache::get("room:{$room->id}:booked_dates"))->toBeNull();
});
