<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\DetailMember;
use App\Models\Notifikasi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($user) {
                // Update google_id if not set
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                }

                Auth::login($user, true);
            } else {
                // Create new user
                $memberRole = Role::where('slug', 'member')->first();

                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => Hash::make(Str::random(24)),
                    'role_id' => $memberRole->id,
                    'email_verified_at' => now(),
                ]);

                DetailMember::create(['user_id' => $user->id]);

                Notifikasi::kirim(
                    $user->id,
                    'Selamat Datang! 🎉',
                    'Akun Anda berhasil dibuat melalui Google. Selamat bermain futsal di Lapsal!',
                    'info'
                );

                Auth::login($user, true);
            }

            $redirect = $user->isAdminOrPetugas()
                ? route('admin.dashboard')
                : route('member.dashboard');

            return redirect($redirect)
                ->with('success', 'Login dengan Google berhasil!');

        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Gagal login dengan Google. Silakan coba lagi.');
        }
    }
}
