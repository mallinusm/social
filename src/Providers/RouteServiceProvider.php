<?php

namespace Social\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Social\Models\User;

/**
 * Class RouteServiceProvider
 * @package Social\Providers
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map(): void
    {
        $this->mapApiRoutes();
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        parent::boot();

        Route::model('user', User::class);
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api/v1')
             ->middleware('api')
             ->group(base_path('routes/api.php'));
    }
}
