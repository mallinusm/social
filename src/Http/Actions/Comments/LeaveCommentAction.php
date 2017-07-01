<?php

namespace Social\Http\Actions\Comments;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Social\Models\{
    Post, User
};
use Social\Repositories\DoctrineCommentRepository;
use Social\Transformers\CommentTransformer;

/**
 * Class LeaveCommentAction
 * @package Social\Http\Actions\Comments
 */
final class LeaveCommentAction
{
    use ValidatesRequests;

    /**
     * @var DoctrineCommentRepository
     */
    private $commentRepository;

    /**
     * @var CommentTransformer
     */
    private $commentTransformer;

    /**
     * LeaveCommentAction constructor.
     * @param DoctrineCommentRepository $commentRepository
     * @param CommentTransformer $commentTransformer
     */
    public function __construct(DoctrineCommentRepository $commentRepository, CommentTransformer $commentTransformer)
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

        /** @var User $author */
        $author =  $request->user();

        return $this->commentTransformer->transform(
            $this->commentRepository->leave(
                $request->input('content'), $post->getId(), $author->getAuthIdentifier()
            )->setUser($author->toUserEntity())
        );
    }
}
