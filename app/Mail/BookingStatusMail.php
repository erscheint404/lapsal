<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public string $previousStatus;

    public function __construct(Booking $booking, string $previousStatus = '')
    {
        $this->booking = $booking;
        $this->previousStatus = $previousStatus;
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->booking->status) {
            'rejected' => 'Booking Ditolak - ' . $this->booking->kode_booking,
            'cancelled' => 'Booking Dibatalkan - ' . $this->booking->kode_booking,
            'completed' => 'Booking Selesai - ' . $this->booking->kode_booking,
            'confirmed' => 'Booking Dikonfirmasi - ' . $this->booking->kode_booking,
            default => 'Update Status Booking - ' . $this->booking->kode_booking,
        };

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-status',
        );
    }
}
