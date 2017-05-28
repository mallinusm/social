<?php

namespace Social\Providers;

use Illuminate\Support\ServiceProvider;
use Social\Contracts\MessageRepository;
use Social\Repositories\QueryBuilderMessageRepository;

/**
 * Class AppServiceProvider
 * @package Social\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    private $singletons = [
        MessageRepository::class => QueryBuilderMessageRepository::class
    ];

    /**
     * @return void
     */
    public function register(): void
    {
        foreach ($this->singletons as $contract => $implementation) {
            $this->app->singleton($contract, $implementation);
        }
    }
}