<?php

namespace Social\Contracts;

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
    public function leave(string $content, int $postId, int $userId): Comment;
}