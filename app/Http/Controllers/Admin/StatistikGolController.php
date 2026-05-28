<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\StatistikGol;
use Illuminate\Http\Request;

class StatistikGolController extends Controller
{
    public function index(Request $request)
    {
        $query = StatistikGol::with('booking.lapangan');

        if ($request->filled('search')) {
            $query->where('nama_pemain', 'like', '%' . $request->search . '%');
        }

        $statistik = $query->latest()->paginate(20);
        $bookings = Booking::where('status', 'completed')->with('lapangan', 'user')->latest()->take(50)->get();

        return view('admin.statistik.index', compact('statistik', 'bookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'pemain' => 'required|array|min:1',
            'pemain.*.nama' => 'required|string|max:100',
            'pemain.*.gol' => 'required|integer|min:0',
        ]);

        foreach ($request->pemain as $pemain) {
            if ($pemain['gol'] > 0) {
                StatistikGol::create([
                    'booking_id' => $request->booking_id,
                    'nama_pemain' => $pemain['nama'],
                    'jumlah_gol' => $pemain['gol'],
                ]);
            }
        }

        return back()->with('success', 'Statistik gol berhasil disimpan.');
    }

    public function destroy(StatistikGol $statistik)
    {
        $statistik->delete();
        return back()->with('success', 'Data statistik berhasil dihapus.');
    }
}
