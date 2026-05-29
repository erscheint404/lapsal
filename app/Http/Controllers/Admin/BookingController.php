<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use App\Services\QrCodeService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'lapangan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_booking', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', '%' . $request->search . '%'));
            });
        }
        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->tanggal);
        }

        $bookings = $query->latest()->paginate(15);

        return view('admin.booking.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'lapangan', 'buktiPembayaran', 'riwayat.changedByUser', 'rating', 'statistikGol']);
        return view('admin.booking.show', compact('booking'));
    }

    public function confirm(Booking $booking, BookingService $bookingService, QrCodeService $qrCodeService)
    {
        if (!in_array($booking->status, ['waiting_confirmation', 'under_review', 'pending_payment'])) {
            return back()->with('error', 'Booking tidak dapat dikonfirmasi.');
        }

        $bookingService->confirmBooking($booking, auth()->id());
        $qrCodeService->generateForBooking($booking);

        return back()->with('success', 'Booking berhasil dikonfirmasi dan QR Code telah digenerate.');
    }

    public function reject(Request $request, Booking $booking, BookingService $bookingService)
    {
        $request->validate(['alasan' => 'required|string|max:500']);

        $bookingService->rejectBooking($booking, $request->alasan, auth()->id());

        return back()->with('success', 'Booking berhasil ditolak.');
    }

    public function complete(Booking $booking, BookingService $bookingService)
    {
        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Hanya booking confirmed yang bisa diselesaikan.');
        }

        $bookingService->completeBooking($booking, auth()->id());

        return back()->with('success', 'Booking berhasil diselesaikan.');
    }

    public function updateStatus(Request $request, Booking $booking, BookingService $bookingService)
    {
        $request->validate(['status' => 'required|in:completed']);
        
        if ($request->status === 'completed') {
            if ($booking->status !== 'confirmed') {
                return back()->with('error', 'Hanya booking dengan status confirmed yang bisa diselesaikan.');
            }
            $bookingService->completeBooking($booking, auth()->id());
        }

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }
}
