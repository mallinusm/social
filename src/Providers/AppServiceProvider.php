<?php

namespace Social\Providers;

use Illuminate\Bus\Dispatcher;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Social\Commands\Reactions\ReactionCommand;
use Social\Commands\Reactions\UndoReactionCommand;
use Social\Contracts\{
    CommentRepository, ConversationRepository, FollowerRepository, MessageRepository, PostRepository,
    ReactionRepository, UserRepository
};
use Social\Handlers\Reactions\ReactionCommandHandler;
use Social\Handlers\Reactions\UndoReactionCommandHandler;
use Social\Models\{
    Comment, Post
};
use Social\Repositories\{
    DoctrineUserRepository, QueryBuilderCommentRepository, QueryBuilderConversationRepository,
    QueryBuilderFollowerRepository, QueryBuilderMessageRepository, QueryBuilderPostRepository,
    QueryBuilderReactionRepository
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
    private $busCommands = [
        ReactionCommand::class => ReactionCommandHandler::class,
        UndoReactionCommand::class => UndoReactionCommandHandler::class
    ];

    /**
     * @var array
     */
    private $singletons = [
        CommentRepository::class => QueryBuilderCommentRepository::class,
        ConversationRepository::class => QueryBuilderConversationRepository::class,
        FollowerRepository::class => QueryBuilderFollowerRepository::class,
        MessageRepository::class => QueryBuilderMessageRepository::class,
        PostRepository::class => QueryBuilderPostRepository::class,
        ReactionRepository::class => QueryBuilderReactionRepository::class,
        UserRepository::class => DoctrineUserRepository::class
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
     * @param Dispatcher $dispatcher
     */
    public function boot(Dispatcher $dispatcher): void
    {
        $dispatcher->map($this->busCommands);

        Relation::morphMap([
            'comments' => Comment::class,
            'posts' => Post::class
        ]);
    }
}
