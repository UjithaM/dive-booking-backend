<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Customer $customer,
        public readonly Booking $booking,
        public readonly array $items,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Booking Confirmation – {$this->booking->booking_reference}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-confirmation',
            with: [
                'customer' => $this->customer,
                'booking'  => $this->booking,
                'items'    => $this->items,
            ],
        );
    }
}
