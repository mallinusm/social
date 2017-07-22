<?php

namespace Social\Repositories;

use Exception;
use Social\Entities\Comment;

/**
 * Class DoctrineCommentRepository
 * @package Social\Repositories
 */
final class DoctrineCommentRepository extends DoctrineRepository
{
    /**
     * @param string $content
     * @param int $postId
     * @param int $userId
     * @return Comment
     * @throws Exception
     */
    public function leave(string $content, int $postId, int $userId): Comment
    {
        $result = $this->getSqlQueryBuilder()
            ->insert('comments')
            ->values([
                'post_id' => '?',
                'content' => '?',
                'user_id' => '?',
                'created_at' => '?',
                'updated_at' => '?',
            ])
            ->setParameter(0, $postId)
            ->setParameter(1, $content)
            ->setParameter(2, $userId)
            ->setParameter(3, $now = $this->freshTimestamp())
            ->setParameter(4, $now)
            ->execute();

        if ($result !== 1) {
            throw new Exception('Could not insert the comment.');
        }

        return (new Comment)->setId($this->lastInsertedId())
            ->setContent($content)
            ->setPostId($postId)
            ->setUserId($userId)
            ->setCreatedAt($now)
            ->setUpdatedAt($now);
    }
}
