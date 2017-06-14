<?php

namespace Social\Http\Actions\Reactions;

use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Http\Request;
use Social\Commands\Reactions\ReactionCommand;
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
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * UpvotePostAction constructor.
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Post $post
     * @param Request $request
     * @return Reactionable
     */
    public function __invoke(Post $post, Request $request): Reactionable
    {
        $user = $request->user();
        $userId = $user->getAuthIdentifier();

        /** @var Reactionable $reactionable */
        $reactionable = $this->dispatcher->dispatchNow(new ReactionCommand(
            $post->getId(), 'posts', 'downvote', $userId
        ));

        return $reactionable->setAttribute('user', $user);
    }
}
