<?php

namespace Social\Providers;

use Illuminate\Bus\Dispatcher;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Social\Contracts\{
    CommentRepository,
    ConversationRepository,
    FollowerRepository,
    MessageRepository,
    PostRepository,
    ReactionableRepository,
    UserRepository
};
use Social\Models\{
    Comment,
    Post
};
use Social\Repositories\{
    DoctrineReactionableRepository,
    DoctrineUserRepository,
    QueryBuilderCommentRepository,
    QueryBuilderConversationRepository,
    QueryBuilderFollowerRepository,
    QueryBuilderMessageRepository,
    QueryBuilderPostRepository
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
        FollowerRepository::class => QueryBuilderFollowerRepository::class,
        MessageRepository::class => QueryBuilderMessageRepository::class,
        PostRepository::class => QueryBuilderPostRepository::class,
        UserRepository::class => DoctrineUserRepository::class,
        ReactionableRepository::class => DoctrineReactionableRepository::class
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

    /**
     * @return void
     */
    public function boot(): void
    {
        Relation::morphMap([
            'comments' => Comment::class,
            'posts' => Post::class
        ]);
    }
}
