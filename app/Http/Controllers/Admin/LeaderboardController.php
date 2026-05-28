<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatistikGol;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
    {
        $leaderboard = StatistikGol::selectRaw('nama_pemain, SUM(jumlah_gol) as total_gol, COUNT(DISTINCT booking_id) as total_sesi')
            ->groupBy('nama_pemain')
            ->orderByDesc('total_gol')
            ->paginate(20);

        return view('admin.leaderboard.index', compact('leaderboard'));
    }
}
