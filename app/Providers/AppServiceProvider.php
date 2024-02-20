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
        $this->app->singleton('PaymentHelper', \App\Services\Payment\HelperFacade::class);

        $this->app->singleton(\App\Contracts\PaymentContract::class, \App\Services\Payment\PaymentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
