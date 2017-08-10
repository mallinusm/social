<?php

namespace Social\Http\Actions\Comments;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Contracts\CommentRepository;
use Social\Models\{
    Post,
    User
};
use Social\Transformers\CommentTransformer;

/**
 * Class LeaveCommentAction
 * @package Social\Http\Actions\Comments
 */
final class LeaveCommentAction
{
    use ValidatesRequests;

    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @var CommentTransformer
     */
    private $commentTransformer;

    /**
     * LeaveCommentAction constructor.
     * @param CommentRepository $commentRepository
     * @param CommentTransformer $commentTransformer
     */
    public function __construct(CommentRepository $commentRepository,
                                CommentTransformer $commentTransformer)
    {
        $this->commentRepository = $commentRepository;
        $this->commentTransformer = $commentTransformer;
    }

    /**
     * @param Post $post
     * @param Request $request
     * @return array
     */
    public function __invoke(Post $post, Request $request): array
    {
        $this->validate($request, [
            'content' => 'required|string|max:255'
        ]);

        /** @var User $user */
        $user =  $request->user();

        $comment = $this->commentRepository->leave($request->input('content'), $post->getId(), $user->getId());

        $comment->setUser($user->toUserEntity());

        return $this->commentTransformer->transform($comment);
    }
}
