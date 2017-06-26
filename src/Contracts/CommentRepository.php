<?php

namespace Social\Contracts;

use Illuminate\Contracts\Pagination\Paginator;
use Social\Models\Comment;

/**
 * Interface CommentRepository
 * @package Social\Contracts
 */
interface CommentRepository
{
    /**
     * @param string $content
     * @param int $postId
     * @param int $userId
     * @return Comment
     */
    function leave(string $content, int $postId, int $userId): Comment;

    /**
     * @param int $postId
     * @return Paginator
     */
    function paginate(int $postId): Paginator;
}
