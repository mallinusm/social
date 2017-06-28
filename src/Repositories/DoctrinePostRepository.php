<?php

namespace Social\Repositories;

use Social\Entities\Post;

/**
 * Class DoctrinePostRepository
 * @package Social\Repositories
 */
final class DoctrinePostRepository extends DoctrineRepository
{
    /**
     * @param int $authorId
     * @param string $content
     * @param int $userId
     * @return Post
     */
    public function publish(int $authorId, string $content, int $userId): Post
    {
        return $this->persist(
            (new Post)->setAuthorId($authorId)
                ->setContent($content)
                ->setUserId($userId)
                ->setCreatedAt($now = $this->freshTimestamp())
                ->setUpdatedAt($now)
        );
    }
}
