<?php

namespace Social\Http\Actions\Reactions;

use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Http\Request;
use Social\Commands\Reactions\ReactionCommand;
use Social\Models\{
    Comment, Reactionable
};

/**
 * Class DownvoteCommentAction
 * @package Social\Http\Actions\Reactions
 */
class DownvoteCommentAction
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
     * @param Comment $comment
     * @param Request $request
     * @return Reactionable
     */
    public function __invoke(Comment $comment, Request $request): Reactionable
    {
        $user = $request->user();
        $userId = $user->getAuthIdentifier();

        /** @var Reactionable $reactionable */
        $reactionable = $this->dispatcher->dispatchNow(new ReactionCommand(
            $comment->getId(), 'comments', 'downvote', $userId
        ));

        return $reactionable->setAttribute('user', $user);
    }
}
