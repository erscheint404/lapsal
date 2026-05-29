<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\RatingLapangan;
use App\Models\SlotWaktu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $lapangan = Cache::remember('home.lapangan', 300, function () {
            return Lapangan::aktif()->take(4)->get();
        });

        // Stats - cached 10 minutes
        $totalBooking = Cache::remember('home.total_booking', 600, function () {
            return Booking::whereIn('status', ['confirmed', 'completed'])->count();
        });

        $totalMember = Cache::remember('home.total_member', 600, function () {
            return User::whereHas('role', fn($q) => $q->where('slug', 'member'))->count();
        });

        $rataRating = Cache::remember('home.rata_rating', 300, function () {
            return round(RatingLapangan::avg('rating') ?? 0, 1);
        });

        // Slot tersisa hari ini - cached 30 seconds
        $slotTersedia = Cache::remember('home.slot_tersedia', 30, function () {
            return SlotWaktu::where('tanggal', today())
                ->where('status', 'available')
                ->where('jam_mulai', '>', now()->format('H:i:s'))
                ->count();
        });

        // Testimoni - cached 5 minutes
        $testimoni = Cache::remember('home.testimoni', 300, function () {
            return RatingLapangan::with(['user', 'lapangan'])
                ->where('rating', '>=', 4)
                ->latest()
                ->take(6)
                ->get();
        });

        // Leaderboard top 5 - cached 1 minute
        $leaderboard = Cache::remember('home.leaderboard', 60, function () {
            return \App\Models\StatistikGol::selectRaw('nama_pemain, SUM(jumlah_gol) as total_gol, COUNT(DISTINCT booking_id) as total_sesi')
                ->groupBy('nama_pemain')
                ->orderByDesc('total_gol')
                ->take(5)
                ->get();
        });

        return view('home', compact(
            'lapangan', 'totalBooking', 'totalMember', 'rataRating',
            'slotTersedia', 'testimoni', 'leaderboard'
        ));
    }
}
