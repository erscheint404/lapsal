<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\SlotWaktu;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LapanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Lapangan::aktif();

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('harga_min')) {
            $query->where('harga_per_jam', '>=', $request->harga_min);
        }

        if ($request->filled('harga_max')) {
            $query->where('harga_per_jam', '<=', $request->harga_max);
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'harga_asc' => $query->orderBy('harga_per_jam', 'asc'),
                'harga_desc' => $query->orderBy('harga_per_jam', 'desc'),
                'nama' => $query->orderBy('nama', 'asc'),
                default => $query->latest(),
            };
        } else {
            $query->latest();
        }

        $lapangan = $query->paginate(12);

        return view('lapangan.index', compact('lapangan'));
    }

    public function show($id)
    {
        $lapangan = Lapangan::with(['fotoLapangan', 'ratings.user'])->findOrFail($id);

        $tanggal = request('tanggal', today()->format('Y-m-d'));

        $slots = SlotWaktu::where('lapangan_id', $id)
            ->where('tanggal', $tanggal)
            ->orderBy('jam_mulai')
            ->get();

        $ratings = $lapangan->ratings()->with('user')->latest()->paginate(5);

        return view('lapangan.show', compact('lapangan', 'slots', 'tanggal', 'ratings'));
    }

    /**
     * API: Get slots for a specific lapangan and date
     */
    public function getSlots($lapanganId, $tanggal)
    {
        $slots = SlotWaktu::where('lapangan_id', $lapanganId)
            ->where('tanggal', $tanggal)
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'jam_mulai' => substr($slot->jam_mulai, 0, 5),
                    'jam_selesai' => substr($slot->jam_selesai, 0, 5),
                    'status' => $slot->status,
                    'label' => $slot->status_label,
                ];
            });

        return response()->json($slots);
    }
}
