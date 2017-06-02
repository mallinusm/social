<?php

namespace Social\Providers;

use Illuminate\Support\ServiceProvider;
use Social\Contracts\{
    CommentRepository, ConversationRepository, MessageRepository, PostRepository, UserRepository
};
use Social\Repositories\{
    QueryBuilderCommentRepository, QueryBuilderConversationRepository, QueryBuilderMessageRepository, QueryBuilderPostRepository, QueryBuilderUserRepository
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
        MessageRepository::class => QueryBuilderMessageRepository::class,
        PostRepository::class => QueryBuilderPostRepository::class,
        UserRepository::class => QueryBuilderUserRepository::class
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