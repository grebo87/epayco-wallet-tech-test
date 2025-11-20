<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the SOAP wallet service contract to its implementation
        // $this->app->bind(
        //     \App\Services\Soap\WalletServiceContract::class,
        //     \App\Services\Soap\WalletService::class
        // );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
