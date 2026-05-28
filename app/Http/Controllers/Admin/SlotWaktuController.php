<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\SlotWaktu;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SlotWaktuController extends Controller
{
    public function index(Request $request)
    {
        $lapanganList = Lapangan::aktif()->get();
        $lapanganId = $request->get('lapangan_id', $lapanganList->first()?->id);
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));

        $slots = collect();
        if ($lapanganId) {
            $slots = SlotWaktu::where('lapangan_id', $lapanganId)
                ->where('tanggal', $tanggal)
                ->orderBy('jam_mulai')
                ->get();
        }

        return view('admin.slot.index', compact('lapanganList', 'lapanganId', 'tanggal', 'slots'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'lapangan_id' => 'required|exists:lapangan,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_buka' => 'required|integer|min:0|max:23',
            'jam_tutup' => 'required|integer|min:1|max:24|gt:jam_buka',
        ]);

        $startDate = Carbon::parse($request->tanggal_mulai);
        $endDate = Carbon::parse($request->tanggal_selesai);
        $count = 0;

        while ($startDate->lte($endDate)) {
            for ($jam = $request->jam_buka; $jam < $request->jam_tutup; $jam++) {
                SlotWaktu::firstOrCreate(
                    [
                        'lapangan_id' => $request->lapangan_id,
                        'tanggal' => $startDate->format('Y-m-d'),
                        'jam_mulai' => sprintf('%02d:00:00', $jam),
                    ],
                    [
                        'jam_selesai' => sprintf('%02d:00:00', $jam + 1),
                        'status' => 'available',
                    ]
                );
                $count++;
            }
            $startDate->addDay();
        }

        return back()->with('success', "Berhasil generate {$count} slot.");
    }

    public function updateStatus(Request $request, SlotWaktu $slot)
    {
        $request->validate([
            'status' => 'required|in:available,blocked',
        ]);

        $slot->update(['status' => $request->status]);

        return back()->with('success', 'Status slot berhasil diubah.');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'slot_ids' => 'required|array',
            'action' => 'required|in:block,unblock,delete',
        ]);

        $slots = SlotWaktu::whereIn('id', $request->slot_ids);

        match ($request->action) {
            'block' => $slots->where('status', 'available')->update(['status' => 'blocked']),
            'unblock' => $slots->where('status', 'blocked')->update(['status' => 'available']),
            'delete' => $slots->whereIn('status', ['available', 'blocked'])->delete(),
        };

        return back()->with('success', 'Slot berhasil diperbarui.');
    }
}
