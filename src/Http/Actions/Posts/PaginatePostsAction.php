<?php

namespace Social\Http\Actions\Posts;

use Illuminate\Contracts\Pagination\Paginator;
use Social\Contracts\PostRepository;
use Social\Models\User;

/**
 * Class PaginatePostsAction
 * @package Social\Http\Actions\Posts
 */
class PaginatePostsAction
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * PaginatePostsAction constructor.
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @param User $user
     * @return Paginator
     */
    public function __invoke(User $user): Paginator
    {
        return $this->postRepository->paginate($user->getAuthIdentifier());
    }
}