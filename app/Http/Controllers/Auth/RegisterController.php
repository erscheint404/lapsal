<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\DetailMember;
use App\Models\Notifikasi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $memberRole = Role::where('slug', 'member')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $memberRole->id,
        ]);

        // Create detail member
        DetailMember::create([
            'user_id' => $user->id,
        ]);

        // Send notification
        Notifikasi::kirim(
            $user->id,
            'Selamat Datang! 🎉',
            'Akun Anda berhasil dibuat. Selamat bermain futsal di Lapsal!',
            'info'
        );

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('member.dashboard')
            ->with('success', 'Registrasi berhasil! Selamat datang di Lapsal Futsal.');
    }
}
