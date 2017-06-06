<?php

namespace Social\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Social\Models\{
    Conversation, Follower, Post, User
};

/**
 * Class RouteServiceProvider
 * @package Social\Providers
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    private $routeModelBindings = [
        'conversation' => Conversation::class,
        'follower' => Follower::class,
        'post' => Post::class,
        'user' => User::class,
    ];

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

        foreach ($this->routeModelBindings as $key => $modelClass) {
            /**
             * @var $this Router
             */
            $this->model($key, $modelClass);
        }
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
