<?php

namespace Social\Http\Actions\Reactions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Social\Contracts\ReactionRepository;
use Social\Models\{
    Post, Reaction
};

/**
 * Class UpvotePostAction
 * @package Social\Http\Actions\Reactions
 */
class UpvotePostAction
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
     * @return Reaction
     * @throws AuthorizationException
     */
    public function __invoke(Post $post, Request $request): Reaction
    {
        $user = $request->user();
        $userId = $request->user()->getAuthIdentifier();

        $postId = $post->getId();

        $reactionTypeId = $this->reactionRepository->getReactionTypeId('upvote');

        if ($this->reactionRepository->hasReacted($postId, 'posts', $reactionTypeId, $userId)) {
            throw new AuthorizationException('This action is unauthorized.');
        }

        return $this->reactionRepository
            ->react($postId, 'posts', $reactionTypeId, $userId)
            ->setAttribute('user', $user);
    }
}
