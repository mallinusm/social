<?php

namespace Social\Http\Actions\Reactions;

use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Http\Request;
use Social\Commands\Reactions\UndoReactionCommand;
use Social\Models\Post;

/**
 * Class UndoDownvotePostAction
 * @package Social\Http\Actions\Reactions
 */
class UndoDownvotePostAction
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
     * @param Post $post
     * @param Request $request
     * @return array
     */
    public function __invoke(Post $post, Request $request): array
    {
        $this->dispatcher->dispatchNow(new UndoReactionCommand(
            $post->getId(), 'posts', 'downvote', $request->user()->getAuthIdentifier()
        ));

        return ['message' => 'Downvote undone.'];
    }
}
