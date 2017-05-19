<?php

namespace Social\Http\Actions\Comments;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Models\{
    Comment,
    Post,
    User
};

/**
 * Class LeaveCommentAction
 * @package Social\Http\Actions\Comments
 */
class LeaveCommentAction
{
    use ValidatesRequests;

    /**
     * @param Post $post
     * @param Request $request
     * @return Comment
     */
    public function __invoke(Post $post, Request $request): Comment
    {
        $this->validate($request, array_except(Comment::$createRules, ['author_id', 'post_id']));

        /**
         * @var User $author
         */
        $author = $request->user();

        return $post->comments()->create([
            'author_id' => $author->getAuthIdentifier(),
            'content' => $request->input('content')
        ]);
    }
}