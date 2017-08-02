<?php

namespace Social\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Social\Contracts\{
    CommentRepository,
    FollowerRepository,
    PostRepository,
    ReactionableRepository,
    ReactionRepository,
    UserRepository
};
use Social\Models\{
    Comment,
    Post
};
use Social\Repositories\{
    DoctrineFollowerRepository,
    DoctrinePostRepository,
    DoctrineReactionableRepository,
    DoctrineReactionRepository,
    DoctrineUserRepository,
    QueryBuilderCommentRepository
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
        FollowerRepository::class => DoctrineFollowerRepository::class,
        PostRepository::class => DoctrinePostRepository::class,
        UserRepository::class => DoctrineUserRepository::class,
        ReactionableRepository::class => DoctrineReactionableRepository::class,
        ReactionRepository::class => DoctrineReactionRepository::class
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
