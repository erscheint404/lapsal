<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/member/dashboard';

    public function showResetForm(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }
}
