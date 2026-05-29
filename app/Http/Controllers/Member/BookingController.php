<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\SlotWaktu;
use App\Services\BookingService;
use App\Services\MidtransService;
use App\Services\SlotLockService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::where('user_id', auth()->id())->with('lapangan');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(10);

        // Auto-complete any confirmed bookings that have passed
        $bookingService = app(\App\Services\BookingService::class);
        foreach ($bookings as $booking) {
            if ($booking->status === 'confirmed') {
                $isPast = $booking->tanggal->isBefore(today()) || 
                         ($booking->tanggal->isToday() && now()->format('H:i:s') > $booking->jam_selesai);
                
                if ($isPast) {
                    $bookingService->completeBooking($booking);
                    $booking->status = 'completed'; // update instance for view
                }
            }
        }

        return view('member.booking.index', compact('bookings'));
    }

    public function create(Request $request)
    {
        $lapanganList = Lapangan::aktif()->get();
        $selectedLapangan = null;
        $slots = collect();
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));

        if ($request->filled('lapangan_id') && $request->filled('slot_ids')) {
            $selectedLapangan = Lapangan::findOrFail($request->lapangan_id);
            $slots = SlotWaktu::where('lapangan_id', $request->lapangan_id)
                ->where('tanggal', $tanggal)
                ->whereIn('id', $request->slot_ids)
                ->orderBy('jam_mulai')
                ->get();
        }

        return view('member.booking.create', compact('lapanganList', 'selectedLapangan', 'slots', 'tanggal'));
    }

    public function lockSlots(Request $request, SlotLockService $slotLockService)
    {
        $request->validate([
            'slot_ids' => 'required|array|min:1',
            'slot_ids.*' => 'exists:slot_waktu,id',
        ]);

        try {
            $locks = $slotLockService->lockMultipleSlots($request->slot_ids, auth()->id());

            $remainingSeconds = $slotLockService->getRemainingSeconds(auth()->id(), $request->slot_ids);

            return response()->json([
                'success' => true,
                'remaining_seconds' => $remainingSeconds,
                'message' => 'Slot berhasil direservasi. Segera selesaikan pembayaran.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function store(Request $request, BookingService $bookingService, MidtransService $midtransService)
    {
        $request->validate([
            'lapangan_id' => 'required|exists:lapangan,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'slot_ids' => 'required|array|min:1',
            'metode_pembayaran' => 'required|in:midtrans,manual',
        ]);

        try {
            $booking = $bookingService->createBooking(
                auth()->id(),
                $request->lapangan_id,
                $request->tanggal,
                $request->slot_ids,
                $request->metode_pembayaran
            );

            // If AJAX request with midtrans, return snap token for inline popup
            if ($request->ajax() && $request->metode_pembayaran === 'midtrans') {
                $snapToken = $midtransService->createSnapToken($booking);
                return response()->json([
                    'success' => true,
                    'snap_token' => $snapToken,
                    'booking_id' => $booking->id,
                ]);
            }

            return redirect()->route('member.booking.checkout', $booking->id)
                ->with('success', 'Booking berhasil dibuat. Silakan selesaikan pembayaran.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(\Illuminate\Http\Request $request, Booking $booking, MidtransService $midtransService, BookingService $bookingService)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Local development webhook fallback via GET parameters
        if ($request->filled('transaction_status') && $request->filled('order_id')) {
            $midtransService->handleNotification($request->all());
            $booking->refresh();
        }

        // Auto-complete if time has passed
        if ($booking->status === 'confirmed') {
            $isPast = $booking->tanggal->isBefore(today()) || 
                     ($booking->tanggal->isToday() && now()->format('H:i:s') > $booking->jam_selesai);
            
            if ($isPast) {
                $bookingService->completeBooking($booking);
                $booking->refresh();
            }
        }

        $snapToken = null;
        if ($booking->metode_pembayaran === 'midtrans' && $booking->status === 'pending_payment') {
            $snapToken = $booking->midtrans_snap_token ?? $midtransService->createSnapToken($booking);
        }

        $booking->load(['lapangan', 'buktiPembayaran', 'riwayat.changedByUser', 'rating', 'statistikGol']);

        return view('member.booking.show', compact('booking', 'snapToken'));
    }

    public function checkout(Booking $booking, MidtransService $midtransService, SlotLockService $slotLockService)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($booking->status, ['pending_payment', 'draft'])) {
            return redirect()->route('member.booking.show', $booking->id);
        }

        $snapToken = null;
        if ($booking->metode_pembayaran === 'midtrans') {
            $snapToken = $booking->midtrans_snap_token ?? $midtransService->createSnapToken($booking);
        }

        // 15 Minutes payment window
        $remainingSeconds = 0;
        if (in_array($booking->status, ['pending_payment', 'draft'])) {
            $diff = now()->diffInSeconds($booking->created_at);
            $remainingSeconds = max(0, 900 - $diff);
        }

        return view('member.booking.checkout', compact('booking', 'snapToken', 'remainingSeconds'));
    }

    public function pay(\Illuminate\Http\Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'payment_method' => 'required|in:midtrans,manual'
        ]);

        if ($request->payment_method === 'manual') {
            if ($booking->metode_pembayaran !== 'manual') {
                $booking->update(['metode_pembayaran' => 'manual']);
            }
            return view('member.booking.manual-payment', compact('booking'));
        }

        if ($booking->metode_pembayaran !== 'midtrans') {
            $booking->update(['metode_pembayaran' => 'midtrans']);
        }
        
        return redirect()->route('member.booking.show', $booking->id);
    }

    public function cancel(Booking $booking, BookingService $bookingService)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$booking->canBeCancelled()) {
            return back()->with('error', 'Booking ini tidak dapat dibatalkan.');
        }

        $bookingService->cancelBooking($booking, auth()->id());

        return redirect()->route('member.booking.index')
            ->with('success', 'Booking berhasil dibatalkan.');
    }
    public function rescheduleForm(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Hanya booking yang sudah dikonfirmasi yang bisa di-reschedule.');
        }

        $tanggal = request('tanggal', $booking->tanggal->format('Y-m-d'));
        $lapangan = $booking->lapangan;

        $slots = SlotWaktu::where('lapangan_id', $lapangan->id)
            ->where('tanggal', $tanggal)
            ->orderBy('jam_mulai')
            ->get();

        return view('member.booking.reschedule', compact('booking', 'lapangan', 'tanggal', 'slots'));
    }

    public function reschedule(Request $request, Booking $booking, BookingService $bookingService)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'tanggal' => 'required|date|after_or_equal:today',
            'slot_ids' => 'required|array|size:' . $booking->durasi_jam,
            'slot_ids.*' => 'exists:slot_waktu,id',
        ], [
            'slot_ids.size' => 'Anda harus memilih tepat ' . $booking->durasi_jam . ' jam (sesuai durasi booking awal).'
        ]);

        try {
            $bookingService->rescheduleBooking($booking, $request->tanggal, $request->slot_ids, auth()->id());

            return redirect()->route('member.booking.show', $booking->id)
                ->with('success', 'Jadwal berhasil di-reschedule. Silakan selesaikan pembayaran biaya tambahan di kasir.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
