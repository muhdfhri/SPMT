<?php

use App\Models\Application;
use Illuminate\Support\Facades\Route;

Route::get('/check-applications', function() {
    $applications = Application::where('status', 'diterima')
        ->select('id', 'status', 'status_magang', 'start_date', 'end_date', 'user_id')
        ->with(['user' => function($q) {
            $q->select('id', 'name', 'email');
        }])
        ->get();
    
    return response()->json($applications, 200, [], JSON_PRETTY_PRINT);
});
