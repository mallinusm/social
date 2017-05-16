<?php

namespace Social\Repositories;

use Social\Contracts\PostRepository;
use Social\Models\Post;

/**
 * Class EloquentPostRepository
 * @package Social\Repositories
 */
class EloquentPostRepository implements PostRepository
{
    /**
     * @var Post
     */
    private $post;

    /**
     * EloquentPostRepository constructor.
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @param int $authorId
     * @param int $content
     * @param int $userId
     * @return Post
     */
    public function publish(int $authorId, int $content, int $userId): Post
    {
        return $this->post->newQuery()->create([
            'author_id' => $authorId,
            'content' => $content,
            'user_id' => $userId
        ]);
    }
}