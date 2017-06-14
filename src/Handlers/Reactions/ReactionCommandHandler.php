<?php

namespace Social\Handlers\Reactions;

use Illuminate\Auth\Access\AuthorizationException;
use Social\Commands\Reactions\ReactionCommand;
use Social\Contracts\ReactionRepository;
use Social\Models\Reactionable;

/**
 * Class ReactionCommandHandler
 * @package Social\Handlers\Reactions
 */
class ReactionCommandHandler
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
     * @param ReactionCommand $reactionCommand
     * @return Reactionable
     * @throws AuthorizationException
     */
    public function handle(ReactionCommand $reactionCommand): Reactionable
    {
        $reactionId = $this->reactionRepository->getReactionId($reactionCommand->getReactionName());

        $reactionableId = $reactionCommand->getReactionableId();

        $reactionableType = $reactionCommand->getReactionableType();

        $userId = $reactionCommand->getUserId();

        if ($this->reactionRepository->hasReacted($reactionableId, $reactionableType, $reactionId, $userId)) {
            throw new AuthorizationException('This action is unauthorized.');
        }

        return $this->reactionRepository->react($reactionableId, $reactionableType, $reactionId, $userId);
    }
}
