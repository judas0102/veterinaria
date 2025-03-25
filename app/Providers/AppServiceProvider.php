<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;

use App\Models\Venta;
use App\Observers\VentaObserver;


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
        //
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['ar','en','fr','es']); // also accepts a closure
        });

        Venta::observe(VentaObserver::class);

    }
}
