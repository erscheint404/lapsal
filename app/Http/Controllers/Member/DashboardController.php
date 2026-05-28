<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $bookingAktif = Booking::where('user_id', $user->id)
            ->whereIn('status', ['pending_payment', 'under_review', 'waiting_confirmation', 'confirmed'])
            ->with('lapangan')
            ->latest()
            ->take(5)
            ->get();

        $bookingSelesai = Booking::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $totalPengeluaran = Booking::where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'completed'])
            ->sum('total_harga');

        $bookingTerbaru = Booking::where('user_id', $user->id)
            ->with('lapangan')
            ->latest()
            ->take(5)
            ->get();

        // Booking yg bisa dirating
        $bisaDirating = Booking::where('user_id', $user->id)
            ->where('status', 'completed')
            ->doesntHave('rating')
            ->with('lapangan')
            ->take(3)
            ->get();

        return view('member.dashboard', compact('bookingAktif', 'bookingSelesai', 'totalPengeluaran', 'bookingTerbaru', 'bisaDirating'));
    }
}
