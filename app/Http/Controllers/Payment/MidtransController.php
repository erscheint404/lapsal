<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class MidtransController extends Controller
{
    /**
     * Handle Midtrans webhook/callback
     */
    public function callback(Request $request, MidtransService $midtransService)
    {
        $notification = $request->all();

        // Verify server key
        $serverKey = config('midtrans.server_key');
        $orderId = $notification['order_id'] ?? '';
        $statusCode = $notification['status_code'] ?? '';
        $grossAmount = $notification['gross_amount'] ?? '';
        $signatureKey = $notification['signature_key'] ?? '';

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $expectedSignature) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $booking = $midtransService->handleNotification($notification);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return response()->json(['message' => 'OK']);
    }
}
