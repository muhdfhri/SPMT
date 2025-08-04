<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth provider
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            \Log::info('Memulai proses callback Google');
            
            try {
                $googleUser = Socialite::driver('google')->user();
                \Log::info('Data dari Google:', [
                    'id' => $googleUser->getId(),
                    'email' => $googleUser->getEmail(),
                    'name' => $googleUser->getName(),
                ]);
            } catch (\Exception $e) {
                \Log::error('Gagal mendapatkan data dari Google: ' . $e->getMessage());
                throw new \Exception('Gagal mendapatkan data dari Google');
            }
            
            // Cari user berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->getId())
                       ->orWhere('email', $googleUser->getEmail())
                       ->first();

            if (!$user) {
                \Log::info('Membuat user baru untuk: ' . $googleUser->getEmail());
                
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(uniqid()), // Generate random password
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => now(), // Verifikasi email otomatis
                ]);
                
                // Beri role default
                $user->assignRole('user');
                \Log::info('User baru berhasil dibuat', ['user_id' => $user->id]);
            } else {
                // Update google_id jika belum ada
                if (empty($user->google_id)) {
                    $user->google_id = $googleUser->getId();
                    $user->save();
                    \Log::info('Google ID berhasil diupdate untuk user yang sudah ada', ['user_id' => $user->id]);
                }
            }

            // Coba login
            if (!Auth::loginUsingId($user->id, true)) {
                \Log::error('Gagal login dengan ID user: ' . $user->id);
                throw new \Exception('Gagal melakukan login');
            }
            \Log::info('Login berhasil', ['user_id' => $user->id]);

            // Cek apakah user memiliki role
            if (!$user->roles || $user->roles->isEmpty()) {
                // Beri role default 'user' jika belum memiliki role
                $user->assignRole('user');
                \Log::info('Memberikan role default ke user', ['user_id' => $user->id]);
            }

            // Redirect ke dashboard
            return redirect()->intended(route('dashboard'));

        } catch (\Exception $e) {
            Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.');
        }
    }
}
