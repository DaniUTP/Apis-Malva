<?php

namespace App\Providers;

use App\Jobs\ReservaJob;
use App\Models\Rol;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::define('propietario-only',function($user){
            return $user->id_rol==2;
        });
        ReservaJob::dispatch();
    }
}
