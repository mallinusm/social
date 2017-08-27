<?php

namespace Social\Contracts\Repositories;

use Doctrine\ORM\EntityNotFoundException;
use Social\Entities\Post;

/**
 * Interface PostRepository
 * @package Social\Contracts
 */
interface PostRepository
{
    /**
     * @param int $authorId
     * @param string $content
     * @param int $userId
     * @return Post
     */
    public function publish(int $authorId, string $content, int $userId): Post;

    /**
     * @param int $id
     * @return bool
     */
    public function unpublish(int $id): bool;

    /**
     * @param array $userIds
     * @return Post[]
     */
    public function paginate(array $userIds): array;

    /**
     * @param int $id
     * @return Post
     * @throws EntityNotFoundException
     */
    public function find(int $id): Post;
}
