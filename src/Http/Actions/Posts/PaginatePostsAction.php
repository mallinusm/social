<?php

namespace Social\Http\Actions\Posts;

use Illuminate\Pagination\Paginator;
use Social\Models\User;

/**
 * Class PaginatePostsAction
 * @package Social\Http\Actions\Posts
 */
class PaginatePostsAction
{
    /**
     * @param User $user
     * @return Paginator
     */
    public function __invoke(User $user): Paginator
    {
        return $user->posts()
            ->with('author', 'comments', 'comments.author')
            ->orderBy('created_at', 'DESC')
            ->simplePaginate();
    }
}