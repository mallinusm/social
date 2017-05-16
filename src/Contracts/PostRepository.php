<?php

namespace Social\Contracts;

use Social\Models\Post;

/**
 * Interface PostRepository
 * @package Social\Contracts
 */
interface PostRepository
{
    /**
     * @param int $authorId
     * @param int $content
     * @param int $userId
     * @return Post
     */
    public function publish(int $authorId, int $content, int $userId): Post;
}