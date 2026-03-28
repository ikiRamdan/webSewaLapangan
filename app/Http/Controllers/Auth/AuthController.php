<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            if (!$user->is_active) {

                Auth::logout();

                return back()->withErrors([
                    'email' => 'Akun tidak aktif'
                ]);
            }

            switch ($user->role) {

                case 'admin':
                    return redirect('/admin/dashboard');

                case 'kasir':
                    return redirect('/kasir/dashboard');

                case 'owner':
                    return redirect('/owner/dashboard');

                default:
                    Auth::logout();
                    return redirect('/login');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah'
        ]);
    }

    public function logout(Request $request)
    {

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}