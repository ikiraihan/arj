<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Halaman Login
     */
    public function loginIndex()
    {
        if (Auth::check()) {
            $redirectTo = Auth::user()->role === 'user' ? 'events' : 'events-list';
            return redirect()->route($redirectTo);
        }

        return view('auth.login');
    }

    /**
     * Proses Login
     */
    public function loginPost(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $request->session()->put('auth.password_confirmed_at', time());

            $redirectTo = Auth::user()->role === 'user' ? 'events' : 'events-list';

            return redirect()->route($redirectTo)
                ->with('success', 'Login berhasil');
        }

        return back()->with('error', 'Email atau password salah');
    }

    /**
     * Halaman Register
     */
    public function registerIndex()
    {
        if (Auth::check()) {
            $redirectTo = Auth::user()->role === 'user' ? 'events' : 'events-list';
            return redirect()->route($redirectTo);
        }
        return view('auth.register');
    }

    /**
     * Proses Register
     */
    public function registerPost(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'password'     => 'required|min:6|confirmed'
        ]);

        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
            'password'     => Hash::make($request->password),
            'role'         => 'user',
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        // samakan dengan login
        $request->session()->put('auth.password_confirmed_at', time());

        $redirectTo = Auth::user()->role === 'user' ? 'events' : 'events-list';

        return redirect()->route($redirectTo)
            ->with('success', 'Register berhasil');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        // revoke sanctum token (kalau pakai API token)
        if ($request->user()) {
            $request->user()->currentAccessToken()?->delete();
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
