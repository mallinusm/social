<?php

namespace Social\Contracts\Repositories;

use Social\Entities\Comment;

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

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
