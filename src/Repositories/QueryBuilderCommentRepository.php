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
     * @param int $authorId
     * @param string $content
     * @param int $postId
     * @return Comment
     */
    public function leave(int $authorId, string $content, int $postId): Comment
    {
        return (new Comment)->fill($this->insert([
            'author_id' => $authorId,
            'content' => $content,
            'created_at' => $now = $this->freshTimestamp(),
            'post_id' => $postId,
            'updated_at' => $now
        ]));
    }
}