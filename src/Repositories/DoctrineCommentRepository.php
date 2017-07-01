<?php

namespace Social\Repositories;

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
     */
    public function leave(string $content, int $postId, int $userId): Comment
    {
        return $this->persist(
            (new Comment)->setContent($content)
                ->setPostId($postId)
                ->setUserId($userId)
                ->setCreatedAt($now = $this->freshTimestamp())
                ->setUpdatedAt($now)
        );
    }
}
