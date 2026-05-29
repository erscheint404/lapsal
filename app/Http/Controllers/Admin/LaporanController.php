<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LaporanPendapatanExport;
use App\Exports\LaporanPenyewaanExport;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function penyewaan(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $bookings = Booking::with(['user', 'lapangan'])
            ->whereIn('status', ['confirmed', 'completed', 'cancelled'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'desc')
            ->get();

        $summary = [
            'total' => $bookings->count(),
            'confirmed' => $bookings->where('status', 'confirmed')->count(),
            'completed' => $bookings->where('status', 'completed')->count(),
            'cancelled' => $bookings->where('status', 'cancelled')->count(),
        ];

        // Per lapangan
        $perLapangan = $bookings->groupBy('lapangan_id')->map(function ($items) {
            return [
                'nama' => $items->first()->lapangan->nama,
                'total' => $items->count(),
                'pendapatan' => $items->whereIn('status', ['confirmed', 'completed'])->sum('total_harga'),
            ];
        });

        if ($request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('admin.laporan.penyewaan-pdf', compact('bookings', 'summary', 'perLapangan', 'startDate', 'endDate'));
            return $pdf->download('laporan-penyewaan-' . $startDate . '-' . $endDate . '.pdf');
        }

        return view('admin.laporan.penyewaan', compact('bookings', 'summary', 'perLapangan', 'startDate', 'endDate'));
    }

    public function pendapatan(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $bookings = Booking::with(['user', 'lapangan'])
            ->whereIn('status', ['confirmed', 'completed'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalPendapatan = $bookings->sum('total_harga');

        // Daily breakdown
        $perHari = $bookings->groupBy(fn($b) => $b->tanggal->format('Y-m-d'))->map(function ($items, $date) {
            return [
                'tanggal' => $date,
                'jumlah_booking' => $items->count(),
                'pendapatan' => $items->sum('total_harga'),
            ];
        })->sortByDesc('tanggal');

        // Chart data
        $chartLabels = $perHari->pluck('tanggal')->values();
        $chartData = $perHari->pluck('pendapatan')->values();

        if ($request->get('export') === 'pdf') {
            $pdf = Pdf::loadView('admin.laporan.pendapatan-pdf', compact('bookings', 'totalPendapatan', 'perHari', 'startDate', 'endDate'));
            return $pdf->download('laporan-pendapatan-' . $startDate . '-' . $endDate . '.pdf');
        }

        return view('admin.laporan.pendapatan', compact('bookings', 'totalPendapatan', 'perHari', 'chartLabels', 'chartData', 'startDate', 'endDate'));
    }

    public function penyewaanExcel(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');
        return Excel::download(
            new LaporanPenyewaanExport($startDate, $endDate),
            'laporan-penyewaan-' . $startDate . '-to-' . $endDate . '.xlsx'
        );
    }

    public function pendapatanExcel(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');
        return Excel::download(
            new LaporanPendapatanExport($startDate, $endDate),
            'laporan-pendapatan-' . $startDate . '-to-' . $endDate . '.xlsx'
        );
    }
}
