<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\FotoLapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LapanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Lapangan::query();

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $lapangan = $query->latest()->paginate(10);
        return view('admin.lapangan.index', compact('lapangan'));
    }

    public function create()
    {
        return view('admin.lapangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga_per_jam' => 'required|numeric|min:0',
            'tipe' => 'required|in:vinyl,rumput_sintetis,semen,parquette',
            'status' => 'required|in:aktif,nonaktif',
            'fasilitas' => 'nullable|array',
            'foto_utama' => 'nullable|image|max:2048',
            'foto_tambahan.*' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['foto_utama', 'foto_tambahan']);

        if ($request->hasFile('foto_utama')) {
            $data['foto_utama'] = $request->file('foto_utama')->store('lapangan', 'public');
        }

        $lapangan = Lapangan::create($data);

        // Upload foto tambahan
        if ($request->hasFile('foto_tambahan')) {
            foreach ($request->file('foto_tambahan') as $index => $foto) {
                $path = $foto->store('lapangan/galeri', 'public');
                FotoLapangan::create([
                    'lapangan_id' => $lapangan->id,
                    'path' => $path,
                    'urutan' => $index,
                ]);
            }
        }

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function show(Lapangan $lapangan)
    {
        $lapangan->load(['fotoLapangan', 'ratings.user', 'bookings' => function($q) {
            $q->latest()->take(10);
        }]);
        return view('admin.lapangan.show', compact('lapangan'));
    }

    public function edit(Lapangan $lapangan)
    {
        $lapangan->load('fotoLapangan');
        return view('admin.lapangan.edit', compact('lapangan'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga_per_jam' => 'required|numeric|min:0',
            'tipe' => 'required|in:vinyl,rumput_sintetis,semen,parquette',
            'status' => 'required|in:aktif,nonaktif',
            'fasilitas' => 'nullable|array',
            'foto_utama' => 'nullable|image|max:2048',
            'foto_tambahan.*' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['foto_utama', 'foto_tambahan']);

        if ($request->hasFile('foto_utama')) {
            if ($lapangan->foto_utama) {
                Storage::disk('public')->delete($lapangan->foto_utama);
            }
            $data['foto_utama'] = $request->file('foto_utama')->store('lapangan', 'public');
        }

        $lapangan->update($data);

        if ($request->hasFile('foto_tambahan')) {
            $maxUrutan = $lapangan->fotoLapangan()->max('urutan') ?? -1;
            foreach ($request->file('foto_tambahan') as $index => $foto) {
                $path = $foto->store('lapangan/galeri', 'public');
                FotoLapangan::create([
                    'lapangan_id' => $lapangan->id,
                    'path' => $path,
                    'urutan' => $maxUrutan + $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil diperbarui.');
    }

    public function destroy(Lapangan $lapangan)
    {
        if ($lapangan->foto_utama) {
            Storage::disk('public')->delete($lapangan->foto_utama);
        }
        foreach ($lapangan->fotoLapangan as $foto) {
            Storage::disk('public')->delete($foto->path);
        }
        $lapangan->delete();

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil dihapus.');
    }

    public function deleteFoto($fotoId)
    {
        $foto = FotoLapangan::findOrFail($fotoId);
        Storage::disk('public')->delete($foto->path);
        $foto->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}
