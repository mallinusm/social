<?php

namespace Social\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Social\Contracts\{
    CommentRepository,
    FollowerRepository,
    PostRepository,
    ReactionableRepository,
    UserRepository
};
use Social\Contracts\Transformers\{
    CommentTransformer as CommentTransformerContract,
    FollowerTransformer as FollowerTransformerContract,
    PostTransformer as PostTransformerContract,
    ReactionableTransformer as ReactionableTransformerContract,
    UserTransformer as UserTransformerContract,
    VoteTransformer as VoteTransformerContract
};
use Social\Repositories\{
    DoctrineCommentRepository,
    DoctrineFollowerRepository,
    DoctrinePostRepository,
    DoctrineReactionableRepository,
    DoctrineUserRepository
};
use Social\Transformers\{
    CommentTransformer,
    FollowerTransformer,
    PostTransformer,
    ReactionableTransformer,
    UserTransformer,
    VoteTransformer
};

/**
 * Class AppServiceProvider
 * @package Social\Providers
 */
final class AppServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    private $singletons = [
        /**
         * Data access objects
         */
        CommentRepository::class      => DoctrineCommentRepository::class,
        FollowerRepository::class     => DoctrineFollowerRepository::class,
        PostRepository::class         => DoctrinePostRepository::class,
        UserRepository::class         => DoctrineUserRepository::class,
        ReactionableRepository::class => DoctrineReactionableRepository::class,
        /**
         * Transformers
         */
        PostTransformerContract::class         => PostTransformer::class,
        UserTransformerContract::class         => UserTransformer::class,
        FollowerTransformerContract::class     => FollowerTransformer::class,
        CommentTransformerContract::class      => CommentTransformer::class,
        ReactionableTransformerContract::class => ReactionableTransformer::class,
        VoteTransformerContract::class         => VoteTransformer::class
    ];

    /**
     * @return void
     */
    public function register(): void
    {
        (new Collection($this->singletons))->each(function(string $implementation, string $contract): void {
            $this->app->singleton($contract, $implementation);
        });
    }
}
