<?php

namespace Social\Http\Actions\Comments;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\CommentRepository;
use Social\Models\{
    Comment, Post
};

/**
 * Class LeaveCommentAction
 * @package Social\Http\Actions\Comments
 */
class LeaveCommentAction
{
    use ValidatesRequests;

    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * LeaveCommentAction constructor.
     * @param CommentRepository $commentRepository
     */
    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param Post $post
     * @param Request $request
     * @return Comment
     */
    public function __invoke(Post $post, Request $request): Comment
    {
        $this->validate($request, array_except(Comment::$createRules, ['author_id', 'post_id']));

        $author =  $request->user();

        return $this->commentRepository->leave(
            $author->getAuthIdentifier(), $request->input('content'), $post->getId()
        )->setAttribute('author', $author);
    }
}