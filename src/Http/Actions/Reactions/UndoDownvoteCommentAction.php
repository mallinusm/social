<?php

namespace Social\Http\Actions\Reactions;

use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Http\Request;
use Social\Commands\Reactions\UndoReactionCommand;
use Social\Models\Comment;

/**
 * Class UndoDownvoteCommentAction
 * @package Social\Http\Actions\Reactions
 */
class UndoDownvoteCommentAction
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
     */
    public function __invoke(Comment $comment, Request $request): array
    {
        $this->dispatcher->dispatchNow(new UndoReactionCommand(
            $comment->getId(), 'comments', 'downvote', $request->user()->getAuthIdentifier()
        ));

        return ['message' => 'Downvote undone.'];
    }
}
