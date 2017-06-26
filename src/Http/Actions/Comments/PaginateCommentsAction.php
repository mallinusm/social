<?php

namespace Social\Http\Actions\Comments;

use Illuminate\Contracts\Pagination\Paginator;
use Social\Contracts\CommentRepository;
use Social\Models\Post;

/**
 * Class PaginateCommentsAction
 * @package Social\Http\Actions\Comments
 */
class PaginateCommentsAction
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * PaginateCommentsAction constructor.
     * @param CommentRepository $commentRepository
     */
    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param Post $post
     * @return Paginator
     */
    public function __invoke(Post $post): Paginator
    {
        return $this->commentRepository->paginate($post->getId());
    }
}
