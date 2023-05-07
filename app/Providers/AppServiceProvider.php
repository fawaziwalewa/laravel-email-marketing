<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Filament::registerRenderHook(
            'footer.start',
            fn (): View => view('footer-logo'),
        );
        Filament::registerRenderHook(
            'sidebar.end',
            fn (): View => view('sidebar-footer'),
        );
        Schema::defaultStringLength(191);
    }
}
