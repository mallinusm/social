<?php

namespace Social\Handlers\Reactionables;

use Illuminate\Auth\Access\AuthorizationException;
use Social\Commands\Reactionables\ReactCommand;
use Social\Contracts\ReactionableRepository;

/**
 * Class ReactCommandHandler
 * @package Social\Handlers\Reactionables
 */
final class ReactCommandHandler
{
    /**
     * @var ReactionableRepository
     */
    private $reactionableRepository;

    /**
     * ReactionCommandHandler constructor.
     * @param ReactionableRepository $reactionableRepository
     */
    public function __construct(ReactionableRepository $reactionableRepository)
    {
        $this->reactionableRepository = $reactionableRepository;
    }

    /**
     * @param ReactCommand $reactCommand
     * @return void
     * @throws AuthorizationException
     */
    public function handle(ReactCommand $reactCommand): void
    {
        $reactionId = $reactCommand->getReactionId();
        $reactionableId = $reactCommand->getReactionableId();
        $reactionableType = $reactCommand->getReactionableType();
        $userId = $reactCommand->getUserId();

        if ($this->reactionableRepository->hasReacted($reactionId, $userId, $reactionableId, $reactionableType)) {
            throw new AuthorizationException('This action is unauthorized.');
        }

        $this->reactionableRepository->react($reactionId, $userId, $reactionableId, $reactionableType);
    }
}
