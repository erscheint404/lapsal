<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BuktiPembayaran;
use App\Services\BookingService;
use App\Services\QrCodeService;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'lapangan', 'buktiPembayaran'])
            ->whereNotNull('metode_pembayaran');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('metode')) {
            $query->where('metode_pembayaran', $request->metode);
        }

        // Default: show under_review first
        $bookings = $query->orderByRaw("FIELD(status, 'under_review', 'pending_payment', 'waiting_confirmation') DESC")
            ->latest()
            ->paginate(15);

        return view('admin.pembayaran.index', compact('bookings'));
    }

    public function verify(Request $request, Booking $booking, BookingService $bookingService, QrCodeService $qrCodeService)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan' => 'nullable|string|max:500',
        ]);

        if ($request->action === 'approve') {
            // Update bukti pembayaran
            if ($booking->buktiPembayaran) {
                $booking->buktiPembayaran->update([
                    'status' => 'verified',
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                    'catatan' => $request->catatan,
                ]);
            }

            $bookingService->confirmBooking($booking, auth()->id());
            $qrCodeService->generateForBooking($booking);

            return back()->with('success', 'Pembayaran diverifikasi dan booking dikonfirmasi.');
        } else {
            if ($booking->buktiPembayaran) {
                $booking->buktiPembayaran->update([
                    'status' => 'rejected',
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                    'catatan' => $request->catatan ?? 'Bukti pembayaran tidak valid',
                ]);
            }

            $bookingService->rejectBooking($booking, $request->catatan ?? 'Pembayaran tidak valid', auth()->id());

            return back()->with('success', 'Pembayaran ditolak.');
        }
    }
}
