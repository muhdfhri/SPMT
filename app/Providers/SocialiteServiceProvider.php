<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;

class SocialiteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Only apply in local environment
        if (app()->environment('local')) {
            $socialite = $this->app->make(Factory::class);
            
            // Configure Google provider
            $socialite->extend('google', function ($app) use ($socialite) {
                $config = $app['config']['services.google'];
                
                return $socialite->buildProvider('Laravel\Socialite\Two\GoogleProvider', $config)
                    ->setHttpClient(new \GuzzleHttp\Client([
                        'verify' => false, // Disable SSL verification
                    ]));
            });
        }
    }
}
