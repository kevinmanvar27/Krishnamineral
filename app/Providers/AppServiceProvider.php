<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Settings;
use App\Models\Vehicle;


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
        Paginator::useBootstrap();    
        
        if (Schema::hasTable('settings')) {
            View::share('settings', Settings::first());
        }
        
        if(Schema::hasTable('vehicles')){
            View::share('vehicles', Vehicle::all());
        }
    }
}
