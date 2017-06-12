<?php

namespace Social\Http\Actions\Reactions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Social\Contracts\ReactionRepository;
use Social\Models\Comment;

/**
 * Class UndoUpvoteCommentAction
 * @package Social\Http\Actions\Reactions
 */
class UndoUpvoteCommentAction
{
    /**
     * @var ReactionRepository
     */
    private $reactionRepository;

    /**
     * UndoUpvotePostAction constructor.
     * @param ReactionRepository $reactionRepository
     */
    public function __construct(ReactionRepository $reactionRepository)
    {
        $this->reactionRepository = $reactionRepository;
    }

    /**
     * @param Comment $comment
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function __invoke(Comment $comment, Request $request): array
    {
        $userId = $request->user()->getAuthIdentifier();

        $reactionId = $this->reactionRepository->getReactionId('upvote');

        if ( ! $this->reactionRepository->hasReacted($comment->getId(), 'comments', $reactionId, $userId)) {
            throw new AuthorizationException('This action is unauthorized.');
        }

        $this->reactionRepository->undoReaction($comment->getId(), 'comments', $reactionId, $userId);

        return ['message' => 'Upvote undone.'];
    }
}
