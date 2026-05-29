<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatistikGol;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LeaderboardController extends Controller
{
    public function index()
    {
        $leaderboard = StatistikGol::selectRaw('nama_pemain, SUM(jumlah_gol) as total_gol, COUNT(DISTINCT booking_id) as total_sesi')
            ->groupBy('nama_pemain')
            ->orderByDesc('total_gol')
            ->take(30)
            ->get();

        $bookings = Booking::where('status', 'completed')
            ->with('lapangan')
            ->latest()
            ->get();

        return view('admin.leaderboard.index', compact('leaderboard', 'bookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'nama_pemain' => 'required|string|max:100',
            'jumlah_gol' => 'required|integer|min:1',
        ]);

        // Check 30 player limit
        $existingPlayers = StatistikGol::distinct()->pluck('nama_pemain')->toArray();
        $isNewPlayer = !in_array($request->nama_pemain, $existingPlayers);

        if ($isNewPlayer && count($existingPlayers) >= 30) {
            return back()->with('error', 'Leaderboard sudah penuh! Maksimal 30 pemain yang dapat terdaftar.');
        }

        StatistikGol::create([
            'booking_id' => $request->booking_id,
            'nama_pemain' => $request->nama_pemain,
            'jumlah_gol' => $request->jumlah_gol,
        ]);

        // Clear public leaderboard cache
        Cache::forget('leaderboard.index');

        return back()->with('success', 'Statistik gol berhasil ditambahkan ke leaderboard.');
    }

    public function destroy($nama_pemain)
    {
        StatistikGol::where('nama_pemain', $nama_pemain)->delete();

        // Clear public leaderboard cache
        Cache::forget('leaderboard.index');

        return back()->with('success', "Pemain {$nama_pemain} berhasil dihapus dari leaderboard.");
    }
}

