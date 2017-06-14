<?php

namespace Social\Handlers\Reactions;

use Illuminate\Auth\Access\AuthorizationException;
use Social\Commands\Reactions\UndoReactionCommand;
use Social\Contracts\ReactionRepository;

/**
 * Class UndoReactionCommandHandler
 * @package Social\Handlers\Reactions
 */
class UndoReactionCommandHandler
{
    /**
     * @var ReactionRepository
     */
    private $reactionRepository;

    /**
     * ReactionCommandHandler constructor.
     * @param ReactionRepository $reactionRepository
     */
    public function __construct(ReactionRepository $reactionRepository)
    {
        $this->reactionRepository = $reactionRepository;
    }

    /**
     * @param UndoReactionCommand $undoReactionCommand
     * @return bool
     * @throws AuthorizationException
     */
    public function handle(UndoReactionCommand $undoReactionCommand): bool
    {
        $reactionId = $this->reactionRepository->getReactionId($undoReactionCommand->getReactionName());

        $reactionableId = $undoReactionCommand->getReactionableId();

        $reactionableType = $undoReactionCommand->getReactionableType();

        $userId = $undoReactionCommand->getUserId();

        if ( ! $this->reactionRepository->hasReacted($reactionableId, $reactionableType, $reactionId, $userId)) {
            throw new AuthorizationException('This action is unauthorized.');
        }

        return $this->reactionRepository->undoReaction($reactionableId, $reactionableType, $reactionId, $userId);
    }
}
