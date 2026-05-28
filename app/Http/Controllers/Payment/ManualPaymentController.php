<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BuktiPembayaran;
use App\Models\Notifikasi;
use App\Models\RiwayatBooking;
use App\Models\User;
use Illuminate\Http\Request;

class ManualPaymentController extends Controller
{
    public function uploadBukti(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'bukti_transfer' => 'required|image|max:5120', // max 5MB
        ]);

        $path = $request->file('bukti_transfer')->store('bukti-pembayaran', 'public');

        // Create or update bukti pembayaran
        BuktiPembayaran::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'file_path' => $path,
                'status' => 'pending',
            ]
        );

        $oldStatus = $booking->status;
        $booking->update([
            'status' => 'under_review',
            'metode_pembayaran' => 'manual',
        ]);

        RiwayatBooking::create([
            'booking_id' => $booking->id,
            'status_lama' => $oldStatus,
            'status_baru' => 'under_review',
            'catatan' => 'Bukti pembayaran diupload',
            'changed_by' => auth()->id(),
        ]);

        // Notify admins
        $admins = User::whereHas('role', fn($q) => $q->whereIn('slug', ['admin', 'petugas']))->get();
        foreach ($admins as $admin) {
            Notifikasi::kirim(
                $admin->id,
                'Pembayaran Manual Baru 💰',
                "Booking {$booking->kode_booking} menunggu verifikasi pembayaran manual.",
                'payment',
                ['booking_id' => $booking->id]
            );
        }

        return redirect()->route('member.booking.show', $booking->id)
            ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }
}
