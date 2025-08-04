<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\MonthlyReport;
use App\Observers\MonthlyReportObserver;
use Illuminate\Support\Facades\Http;

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

        // Skip SSL verification in local environment
        if (app()->environment('local')) {
            Http::macro('withoutSslVerification', function () {
                return Http::withOptions([
                    'verify' => false,
                ]);
            });
        }
    }
}
