<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Mail;

class PublicPageController extends Controller
{
    public function index()
    {
        return view('pages.index');
    }

    public function rooms(Request $request)
    {
        $dateRange = $request->input('dateRange');
        return view('pages.rooms', compact('dateRange'));
    }

    public function showRoom(Room $room)
    {
        $otherRooms = Room::where('actived', 1)
            ->where('id', '!=', $room->id)
            ->inRandomOrder()
            ->limit(6)
            ->orderByRaw("CAST(SUBSTRING_INDEX(name, ' ', -1) AS UNSIGNED)")
            ->get();

        return view('pages.room', compact('room', 'otherRooms'));
    }

    public function gallery()
    {
        $images = Image::whereHas('room', fn($query) => $query->where('actived', 1))
            ->inRandomOrder()
            ->limit(20)
            ->get()
            ->pluck('path');

        return view('pages.gallery', compact('images'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        if (app()->environment('local')) {
            // return new ContactMessage($validated);
        }

        Mail::to(config('mail.contact_to'))->send(new ContactMessage($validated));

        return back()->with('success', 'Your message has been sent!');
    }
}
