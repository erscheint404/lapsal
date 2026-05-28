<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $settings = Pengaturan::all()->groupBy('group');
        return view('admin.pengaturan.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token', '_method') as $key => $value) {
            Pengaturan::setValue($key, $value ?? '');
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
