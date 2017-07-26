<?php

namespace Social\Contracts;

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
    function publish(int $authorId, string $content, int $userId): Post;

    /**
     * @param int $id
     * @return bool
     */
    function unpublish(int $id): bool;

    /**
     * @param array $userIds
     * @return Post[]
     */
    function paginate(array $userIds): array;
}
