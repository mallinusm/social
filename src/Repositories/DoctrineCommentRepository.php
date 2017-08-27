<?php

namespace Social\Repositories;

use Exception;
use Social\Contracts\Repositories\CommentRepository;
use Social\Entities\Comment;

/**
 * Class DoctrineCommentRepository
 * @package Social\Repositories
 */
final class DoctrineCommentRepository extends DoctrineRepository implements CommentRepository
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
                'post_id' => ':postId',
                'content' => ':content',
                'user_id' => ':userId',
                'created_at' => ':now',
                'updated_at' => ':now',
            ])
            ->setParameters([
                'postId' => $postId,
                'content' => $content,
                'userId' => $userId,
                'now' => $now = $this->freshTimestamp()
            ])
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
