<?php

namespace Social\Http\Actions\Reactions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Social\Contracts\ReactionRepository;
use Social\Models\{
    Post, Reactionable
};

/**
 * Class DownvotePostAction
 * @package Social\Http\Actions\Reactions
 */
class DownvotePostAction
{
    /**
     * @var ReactionRepository
     */
    private $reactionRepository;

    /**
     * UpvotePostAction constructor.
     * @param ReactionRepository $reactionRepository
     */
    public function __construct(ReactionRepository $reactionRepository)
    {
        $this->reactionRepository = $reactionRepository;
    }

    /**
     * @param Post $post
     * @param Request $request
     * @return Reactionable
     * @throws AuthorizationException
     */
    public function __invoke(Post $post, Request $request): Reactionable
    {
        $user = $request->user();
        $userId = $request->user()->getAuthIdentifier();

        $postId = $post->getId();

        $reactionId = $this->reactionRepository->getReactionId('downvote');

        if ($this->reactionRepository->hasReacted($postId, 'posts', $reactionId, $userId)) {
            throw new AuthorizationException('This action is unauthorized.');
        }

        return $this->reactionRepository
            ->react($postId, 'posts', $reactionId, $userId)
            ->setAttribute('user', $user);
    }
}
