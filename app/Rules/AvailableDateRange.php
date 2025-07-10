<?php

namespace App\Rules;

use App\Models\Reservation;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AvailableDateRange implements ValidationRule
{
    protected $roomId;
    protected $dateCheckout;
    protected $reservation;

    public function __construct($roomId, $dateCheckout, $reservation)
    {
        $this->roomId = $roomId;
        $this->dateCheckout = $dateCheckout;
        $this->reservation = $reservation;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $checkin = $value;
        $checkout = $this->dateCheckout;
        $conflict = Reservation::where('room_id', $this->roomId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereNot('id', $this->reservation)
            ->where(function ($query) use ($checkin, $checkout) {
                $query->where('date_checkin', '<', $checkout)
                    ->where('date_checkout', '>', $checkin);
            })
            ->exists();

        if ($conflict) {
            $fail("The room is already occupied in the selected period.");
        }
    }
}
