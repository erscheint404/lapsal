<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\User;
use App\Models\RatingLapangan;
use App\Models\SlotWaktu;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats cards
        $totalBookingHariIni = Booking::whereDate('tanggal', today())->count();
        $totalPendapatan = Booking::whereIn('status', ['confirmed', 'completed'])->sum('total_harga');
        $pendapatanBulanIni = Booking::whereIn('status', ['confirmed', 'completed'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_harga');
        $totalMember = User::whereHas('role', fn($q) => $q->where('slug', 'member'))->count();
        $totalLapangan = Lapangan::aktif()->count();
        $rataRating = round(RatingLapangan::avg('rating') ?? 0, 1);

        // Booking terbaru
        $bookingTerbaru = Booking::with(['user', 'lapangan'])
            ->latest()
            ->take(10)
            ->get();

        // Booking perlu aksi
        $bookingPerluAksi = Booking::with(['user', 'lapangan'])
            ->whereIn('status', ['under_review', 'waiting_confirmation', 'pending_payment'])
            ->latest()
            ->take(10)
            ->get();

        // Chart data: booking per hari (7 hari terakhir)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartData[] = Booking::whereDate('created_at', $date)->count();
        }

        // Pendapatan per bulan (6 bulan terakhir)
        $pendapatanLabels = [];
        $pendapatanData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $pendapatanLabels[] = $date->format('M Y');
            $pendapatanData[] = Booking::whereIn('status', ['confirmed', 'completed'])
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_harga');
        }

        return view('admin.dashboard', compact(
            'totalBookingHariIni', 'totalPendapatan', 'pendapatanBulanIni',
            'totalMember', 'totalLapangan', 'rataRating',
            'bookingTerbaru', 'bookingPerluAksi',
            'chartLabels', 'chartData', 'pendapatanLabels', 'pendapatanData'
        ));
    }
}
