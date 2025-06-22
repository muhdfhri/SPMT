<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckActiveInternship
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        
        $hasValidInternship = DB::table('applications')
            ->where('user_id', $user->id)
            ->where('status', 'diterima')
            ->whereIn('status_magang', ['in_progress', 'completed'])
            ->exists();
            
        if (!$hasValidInternship) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Anda tidak memiliki akses. Hanya untuk mahasiswa yang sedang atau telah menyelesaikan magang.');
        }

        return $next($request);
    }
}
