<?php

namespace App\Mail;

use App\Models\Room;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Carbon;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;


class ReservationMessage extends Mailable
{
    use Queueable, SerializesModels, ShouldQueue;

    public array $data;
    public Room $room;

    /**
     * Create a new message instance.
     */
    public function __construct(array $data, Room $room)
    {
        $this->data = $data;
        $this->data['date_checkin'] = Carbon::parse($this->data['date_checkin'])->translatedFormat('F j, Y');
        $this->data['date_checkout'] = Carbon::parse($this->data['date_checkout'])->translatedFormat('F j, Y');
        $this->room = $room;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Reservation Form Message',
        );
    }
    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reservation',
            with: [
                'data' => $this->data,
                'room' => $this->room
            ],
        );
    }
}
