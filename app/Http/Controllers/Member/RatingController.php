<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\RatingLapangan;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$booking->canBeRated()) {
            return back()->with('error', 'Booking ini tidak bisa dirating.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:500',
        ]);

        RatingLapangan::create([
            'lapangan_id' => $booking->lapangan_id,
            'booking_id' => $booking->id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'ulasan' => $request->ulasan,
        ]);

        return back()->with('success', 'Terima kasih atas rating Anda! ⭐');
    }
}
