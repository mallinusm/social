<?php

namespace Social\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Social\Models\{
    Follower, Message, Post
};
use Social\Policies\{
    FollowerPolicy, MessagePolicy, PostPolicy
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
        Follower::class => FollowerPolicy::class,
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
    }
}
