<?php

namespace Social\Repositories;

use Illuminate\Contracts\Pagination\Paginator;
use Social\Contracts\PostRepository;
use Social\Models\Post;

/**
 * Class QueryBuilderPostRepository
 * @package Social\Repositories
 */
class QueryBuilderPostRepository extends QueryBuilderRepository implements PostRepository
{
    /**
     * @return string
     */
    protected function getTable(): string
    {
        return 'posts';
    }

    /**
     * @param int $authorId
     * @param string $content
     * @param int $userId
     * @return Post
     */
    public function publish(int $authorId, string $content, int $userId): Post
    {
        return (new Post)->fill($this->insert([
            'author_id' => $authorId,
            'content' => $content,
            'created_at' => $now = $this->freshTimestamp(),
            'updated_at' => $now,
            'user_id' => $userId
        ]));
    }

    /**
     * @param int $userId
     * @return Paginator
     */
    public function paginate(int $userId): Paginator
    {
        return (new Post)->newQuery()
            ->with('author', 'comments', 'comments.user')
            ->where('user_id', $userId)
            ->latest()
            ->simplePaginate();
    }
}