<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\MonthlyReport;
use App\Observers\MonthlyReportObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the MonthlyReport observer
        MonthlyReport::observe(MonthlyReportObserver::class);
    }
}
