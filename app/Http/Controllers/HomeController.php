<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\RatingLapangan;
use App\Models\SlotWaktu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $lapangan = Lapangan::aktif()->take(4)->get();

        // Stats
        $totalBooking = Booking::whereIn('status', ['confirmed', 'completed'])->count();
        $totalMember = User::whereHas('role', fn($q) => $q->where('slug', 'member'))->count();
        $rataRating = round(RatingLapangan::avg('rating') ?? 0, 1);

        // Slot tersisa hari ini
        $slotTersedia = SlotWaktu::where('tanggal', today())
            ->where('status', 'available')
            ->where('jam_mulai', '>', now()->format('H:i:s'))
            ->count();

        // Testimoni
        $testimoni = RatingLapangan::with(['user', 'lapangan'])
            ->where('rating', '>=', 4)
            ->latest()
            ->take(6)
            ->get();

        // Leaderboard top 5
        $leaderboard = \App\Models\StatistikGol::selectRaw('nama_pemain, SUM(jumlah_gol) as total_gol, COUNT(DISTINCT booking_id) as total_sesi')
            ->groupBy('nama_pemain')
            ->orderByDesc('total_gol')
            ->take(5)
            ->get();

        return view('home', compact(
            'lapangan', 'totalBooking', 'totalMember', 'rataRating',
            'slotTersedia', 'testimoni', 'leaderboard'
        ));
    }
}
