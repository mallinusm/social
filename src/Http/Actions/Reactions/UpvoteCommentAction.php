<?php

namespace Social\Http\Actions\Reactions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Social\Contracts\ReactionRepository;
use Social\Models\{
    Comment, Reactionable
};

/**
 * Class UpvoteCommentAction
 * @package Social\Http\Actions\Reactions
 */
class UpvoteCommentAction
{
    /**
     * @var ReactionRepository
     */
    private $reactionRepository;

    /**
     * UpvoteCommentAction constructor.
     * @param ReactionRepository $reactionRepository
     */
    public function __construct(ReactionRepository $reactionRepository)
    {
        $this->reactionRepository = $reactionRepository;
    }

    /**
     * @param Comment $comment
     * @param Request $request
     * @return Reactionable
     * @throws AuthorizationException
     */
    public function __invoke(Comment $comment, Request $request): Reactionable
    {
        $user = $request->user();
        $userId = $request->user()->getAuthIdentifier();

        $commentId = $comment->getId();

        $reactionId = $this->reactionRepository->getReactionId('upvote');

        if ($this->reactionRepository->hasReacted($commentId, 'comments', $reactionId, $userId)) {
            throw new AuthorizationException('This action is unauthorized.');
        }

        return $this->reactionRepository
            ->react($commentId, 'comments', $reactionId, $userId)
            ->setAttribute('user', $user);
    }
}
