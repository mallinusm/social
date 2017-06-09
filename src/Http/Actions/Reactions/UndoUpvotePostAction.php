<?php

namespace Social\Http\Actions\Reactions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Social\Contracts\ReactionRepository;
use Social\Models\Post;

/**
 * Class UndoUpvotePostAction
 * @package Social\Http\Actions\Reactions
 */
class UndoUpvotePostAction
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
     * @param Post $post
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function __invoke(Post $post, Request $request): array
    {
        $userId = $request->user()->getAuthIdentifier();

        $reactionId = $this->reactionRepository->getReactionId('upvote');

        if ( ! $this->reactionRepository->hasReacted($post->getId(), 'posts', $reactionId, $userId)) {
            throw new AuthorizationException('This action is unauthorized.');
        }

        $this->reactionRepository->undoReaction($post->getId(), 'posts', $reactionId, $userId);

        return ['message' => 'Upvote undone.'];
    }
}
