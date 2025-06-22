<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Override login to handle AJAX
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            if ($request->ajax()) {
                return response()->json(['error' => 'Terlalu banyak percobaan login. Silakan coba lagi nanti.'], 429);
            }

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            $user = Auth::user();
            Auth::login($user, $request->filled('remember'));
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true, 
                    'redirect' => $user->role === 'admin' ? route('admin.dashboard') : route('mahasiswa.dashboard')
                ]);
            }
            
            return redirect()->intended(
                $user->role === 'admin' ? route('admin.dashboard') : route('mahasiswa.dashboard')
            );
        }

        $this->incrementLoginAttempts($request);

        if ($request->ajax()) {
            return response()->json(['error' => 'Email atau password salah.'], 401);
        }

        return back()->withErrors([
            $this->username() => trans('auth.failed'),
        ]);
    }
}