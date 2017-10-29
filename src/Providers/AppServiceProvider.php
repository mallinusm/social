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
use Social\Contracts\Services\{
    AuthenticationService,
    TransformerService
};
use Social\Contracts\Transformers\{
    CommentTransformer      as CommentTransformerContract,
    PostTransformer         as PostTransformerContract,
    ReactionableTransformer as ReactionableTransformerContract,
    VoteTransformer         as VoteTransformerContract
};
use Social\Repositories\{
    DoctrineCommentRepository,
    DoctrineFollowerRepository,
    DoctrinePostRepository,
    DoctrineReactionableRepository,
    DoctrineUserRepository
};
use Social\Services\{
    FractalTransformerService,
    LaravelAuthenticationService
};
use Social\Transformers\{
    CommentTransformer,
    PostTransformer,
    ReactionableTransformer,
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
        TransformerService::class => FractalTransformerService::class,
        /**
         * Transformers
         */
        CommentTransformerContract::class      => CommentTransformer::class,
        PostTransformerContract::class         => PostTransformer::class,
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
