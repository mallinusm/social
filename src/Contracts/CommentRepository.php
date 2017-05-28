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
     * @param int $authorId
     * @param string $content
     * @param int $postId
     * @return Comment
     */
    public function leave(int $authorId, string $content, int $postId): Comment;
}