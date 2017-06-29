<?php

namespace Social\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Social\Models\{
    Comment, Conversation, Follower, Post, User
};

/**
 * Class RouteServiceProvider
 * @package Social\Providers
 */
final class RouteServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    private $routeModelBindings = [
        'comment' => Comment::class,
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

        $this->mapCdnRoutes();
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
    private function mapApiRoutes(): void
    {
        /**
         * @var $this Router
         */
        $this->group([
            'prefix' => 'api/v1',
            'middleware' => 'api'
        ], base_path('routes/api.php'));
    }

    /**
     * @return void
     */
    private function mapCdnRoutes(): void
    {
        /**
         * @var $this Router
         */
        $this->group([
            'prefix' => 'cdn',
            'middleware' => 'cdn'
        ], base_path('routes/cdn.php'));
    }
}
