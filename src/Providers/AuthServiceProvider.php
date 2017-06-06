<?php

namespace Social\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Social\Models\{
    Message, Post
};
use Social\Policies\{
    MessagePolicy, PostPolicy
};

/**
 * Class AuthServiceProvider
 * @package Social\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Message::class => MessagePolicy::class,
        Post::class => PostPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Passport::routes(null , [
            'prefix' => 'api/v1/oauth',
            'middleware' => \Barryvdh\Cors\HandleCors::class,
        ]);
    }
}
