<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
{
    if (!$request->expectsJson()) {
        // Log untuk debugging
        \Log::info('Unauthenticated access', [
            'url' => $request->fullUrl(),
            'ajax' => $request->ajax(),
            'wantsJson' => $request->wantsJson(),
            'acceptsJson' => $request->acceptsJson(),
            'user' => auth()->check() ? auth()->id() : 'guest'
        ]);
        
        return route('login');
    }
    
    return null;
}
}