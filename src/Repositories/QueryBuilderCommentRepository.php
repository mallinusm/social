<?php

namespace Social\Repositories;

use Social\Contracts\CommentRepository;
use Social\Models\Comment;

/**
 * Class QueryBuilderCommentRepository
 * @package Social\Repositories
 */
class QueryBuilderCommentRepository extends QueryBuilderRepository implements CommentRepository
{
    /**
     * @return string
     */
    protected function getTable(): string
    {
        return 'comments';
    }

    /**
     * @param string $content
     * @param int $postId
     * @param int $userId
     * @return Comment
     */
    public function leave(string $content, int $postId, int $userId): Comment
    {
        return (new Comment)->fill($this->insert([
            'content' => $content,
            'created_at' => $now = $this->freshTimestamp(),
            'post_id' => $postId,
            'updated_at' => $now,
            'user_id' => $userId,
        ]));
    }
}