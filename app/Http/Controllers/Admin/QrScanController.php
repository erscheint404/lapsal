<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use App\Services\QrCodeService;
use Illuminate\Http\Request;

class QrScanController extends Controller
{
    public function index()
    {
        return view('admin.qrscan.index');
    }

    public function validate_qr(Request $request, QrCodeService $qrCodeService)
    {
        $request->validate(['qr_data' => 'required|string']);

        $booking = $qrCodeService->validateQrCode($request->qr_data);

        if (!$booking) {
            return response()->json([
                'valid' => false,
                'message' => 'QR Code tidak valid atau booking tidak ditemukan.',
            ]);
        }

        $booking->load(['user', 'lapangan']);

        return response()->json([
            'valid' => true,
            'booking' => [
                'id' => $booking->id,
                'kode' => $booking->kode_booking,
                'member' => $booking->user->name,
                'lapangan' => $booking->lapangan->nama,
                'tanggal' => $booking->tanggal->format('d M Y'),
                'waktu' => $booking->waktu,
                'status' => $booking->status,
                'status_label' => $booking->status_label,
            ],
        ]);
    }

    public function process(Request $request)
    {
        $request->validate(['kode_booking' => 'required|string']);

        $booking = Booking::where('kode_booking', $request->kode_booking)
            ->with(['user', 'lapangan'])
            ->first();

        if (!$booking) {
            return back()
                ->with('error_scan', 'Kode booking tidak ditemukan atau tidak valid.')
                ->with('error', 'Tiket Tidak Valid atau tidak ditemukan.');
        }

        return back()
            ->with('booking', $booking)
            ->with('success', 'Tiket Valid! Data booking berhasil dimuat.');
    }
}
