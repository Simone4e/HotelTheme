<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Enum\ReservationStatus;
use Illuminate\Validation\Rule;
use App\Mail\ReservationMessage;
use App\Rules\AvailableDateRange;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\ReservationCustomerConfirmationMail;

class ReservationController extends Controller
{
    /**
     * Display a listing of the reservations.
     */
    public function index()
    {
        $reservations = Reservation::paginate();

        return view('pages.admin.reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new reservation.
     */
    public function create()
    {
        $statusOptions = collect(ReservationStatus::cases())
            ->mapWithKeys(fn($s) => [$s->value => $s->label()])
            ->toArray();

        $rooms = Room::where('actived', true)
            ->pluck('name', 'id')
            ->toArray();

        $booked = collect();

        return view('pages.admin.reservations.create', compact('statusOptions', 'rooms', 'booked'));
    }

    /**
     * Store a newly created reservation (Admin side).
     */
    public function store(Request $request)
    {
        $validated = $this->validateReservation($request);

        Reservation::create($validated);

        return redirect()
            ->route('admin.reservations.index')
            ->with('success', 'Reservation created.');
    }

    /**
     * Store a reservation from the public site.
     */
    public function storeUser(Room $room, Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            $this->rules($request)
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('openReservation', true);
        }

        $validated = $validator->validated();

        if ($validated['phone'] === null && $validated['email'] === null) {
            return back()
                ->withErrors(['phone' => 'Phone or Email must not be empty.'])
                ->withInput()
                ->with('openReservation', true);
        }

        Reservation::create($validated);

        if (app()->environment('local')) {
            //return new ReservationMessage($validated, $room);
            //return new ReservationCustomerConfirmationMail($validated, $room);
        }

        /*
            Set queue or send if you don't need it
            ex: Mail::to(config('mail.contact_to'))->send(new ReservationMessage($validated, $room));
        */
        Mail::to(config('mail.contact_to'))->queue(new ReservationMessage($validated, $room));

        if ($validated['email']) {
            Mail::to(config('mail.contact_to'))->queue(new ReservationCustomerConfirmationMail($validated, $room));
        }

        return redirect()
            ->route('rooms.show', $room)
            ->with('success', 'Reservation created successfully!');
    }

    /**
     * Show the form for editing a reservation.
     */
    public function edit(Reservation $reservation)
    {
        $statusOptions = collect(ReservationStatus::cases())
            ->mapWithKeys(fn($s) => [$s->value => $s->label()])
            ->toArray();

        $rooms = Room::where('actived', true)
            ->pluck('name', 'id')
            ->toArray();

        $booked = $reservation->room->reservations()
            ->where('id', '!=', $reservation->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->map(fn($r) => [
                Carbon::parse($r->date_checkin)->format('Y-m-d'),
                Carbon::parse($r->date_checkout)->format('Y-m-d'),
            ]);

        return view('pages.admin.reservations.edit', compact('reservation', 'booked', 'rooms', 'statusOptions'));
    }

    /**
     * Update the specified reservation.
     */
    public function update(Request $request, Reservation $reservation)
    {
        $validated = $this->validateReservation($request, $reservation->id);

        $reservation->update($validated);

        return redirect()
            ->route('admin.reservations.index')
            ->with('success', 'Reservation edited.');
    }

    /**
     * Get validation rules for a reservation.
     */
    protected function rules(Request $request, ?int $ignoreReservationId = null): array
    {
        return [
            'name_client'   => 'required|string|max:255',
            'email'         => 'string|email|nullable',
            'phone'         => 'string|nullable',
            'room_id'       => ['required', 'integer', Rule::exists('rooms', 'id')],
            'date_checkin'  => [
                'required',
                'date',
                new AvailableDateRange(
                    $request->input('room_id'),
                    $request->input('date_checkout'),
                    $ignoreReservationId
                ),
            ],
            'date_checkout' => 'required|date',
            'messages'      => 'required|string',
            'status'        => [Rule::enum(ReservationStatus::class), 'nullable'], // Admin form enforces it as required
        ];
    }

    /**
     * Validate reservation with shared rules.
     */
    protected function validateReservation(Request $request, ?int $ignoreReservationId = null): array
    {
        $rules = $this->rules($request, $ignoreReservationId);

        // Remove status if not coming from admin route
        if (!$request->has('status')) {
            unset($rules['status']);
        }

        $validated = $request->validate($rules);

        if (($validated['phone'] ?? null) === null && ($validated['email'] ?? null) === null) {
            back()->withErrors(['phone' => 'Phone or Email must not be empty.'])->withInput();
        }

        return $validated;
    }
}
