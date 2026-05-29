<?php

namespace App\Http\Controllers;

use App\Models\StatistikGol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LeaderboardController extends Controller
{
    public function index()
    {
        $leaderboard = Cache::remember('leaderboard.index', 60, function () {
            return StatistikGol::selectRaw('nama_pemain, SUM(jumlah_gol) as total_gol, COUNT(DISTINCT booking_id) as total_sesi')
                ->groupBy('nama_pemain')
                ->orderByDesc('total_gol')
                ->paginate(30);
        });

        return view('leaderboard.index', compact('leaderboard'));
    }
}
