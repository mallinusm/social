<?php

namespace Social\Http\Actions\Reactions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Http\Request;
use Social\Commands\Reactions\UndoReactionCommand;
use Social\Models\Comment;

/**
 * Class UndoUpvoteCommentAction
 * @package Social\Http\Actions\Reactions
 */
class UndoUpvoteCommentAction
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * UndoUpvoteCommentAction constructor.
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Comment $comment
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function __invoke(Comment $comment, Request $request): array
    {
        $this->dispatcher->dispatchNow(new UndoReactionCommand(
            $comment->getId(), 'comments', 'upvote', $request->user()->getAuthIdentifier()
        ));

        return ['message' => 'Upvote undone.'];
    }
}
