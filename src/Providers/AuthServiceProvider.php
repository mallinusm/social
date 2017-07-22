<?php

namespace Social\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Class AuthServiceProvider
 * @package Social\Providers
 */
final class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //
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
