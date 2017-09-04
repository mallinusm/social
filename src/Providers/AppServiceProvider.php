<?php

namespace Social\Providers;

use Illuminate\Support\{
    Collection,
    ServiceProvider
};
use Social\Contracts\Repositories\{
    CommentRepository,
    FollowerRepository,
    PostRepository,
    ReactionableRepository,
    UserRepository
};
use Social\Contracts\Services\AuthenticationService;
use Social\Contracts\Transformers\{
    CommentTransformer      as CommentTransformerContract,
    FollowerTransformer     as FollowerTransformerContract,
    PostTransformer         as PostTransformerContract,
    ReactionableTransformer as ReactionableTransformerContract,
    UserTransformer         as UserTransformerContract,
    VoteTransformer         as VoteTransformerContract
};
use Social\Repositories\{
    DoctrineCommentRepository,
    DoctrineFollowerRepository,
    DoctrinePostRepository,
    DoctrineReactionableRepository,
    DoctrineUserRepository
};
use Social\Services\LaravelAuthenticationService;
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
        ReactionableRepository::class => DoctrineReactionableRepository::class,
        UserRepository::class         => DoctrineUserRepository::class,
        /**
         * Services
         */
        AuthenticationService::class => LaravelAuthenticationService::class,
        /**
         * Transformers
         */
        CommentTransformerContract::class      => CommentTransformer::class,
        FollowerTransformerContract::class     => FollowerTransformer::class,
        PostTransformerContract::class         => PostTransformer::class,
        ReactionableTransformerContract::class => ReactionableTransformer::class,
        UserTransformerContract::class         => UserTransformer::class,
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
