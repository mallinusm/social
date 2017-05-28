<?php

namespace Social\Providers;

use Illuminate\Support\ServiceProvider;
use Social\Contracts\{
    CommentRepository, ConversationRepository, MessageRepository
};
use Social\Repositories\{
    QueryBuilderCommentRepository, QueryBuilderConversationRepository, QueryBuilderMessageRepository
};

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
        CommentRepository::class => QueryBuilderCommentRepository::class,
        ConversationRepository::class => QueryBuilderConversationRepository::class,
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