<?php

namespace Social\Contracts;

use Illuminate\Contracts\Pagination\Paginator;
use Social\Models\Post;

/**
 * Interface PostRepository
 * @package Social\Contracts
 */
interface PostRepository
{
    /**
     * @param int $authorId
     * @param string $content
     * @param int $userId
     * @return Post
     */
    function publish(int $authorId, string $content, int $userId): Post;

    /**
     * @param array $userIds
     * @return Paginator
     */
    function paginate(array $userIds): Paginator;
}
