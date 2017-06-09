<?php

namespace Social\Repositories;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
            ->with('author', 'comments.user')
            ->with(['comments' => function(HasMany $query) {
                $query->getQuery()->latest()->take(10);
            }])
            ->where('user_id', $userId)
            ->withCount(['hasReacted as has_upvoting' => function(Builder $query) {
                $query->where('name', 'upvote');
            }])
            ->withCount(['hasReacted as has_downvoting' => function(Builder $query) {
                $query->where('name', 'downvote');
            }])
            ->withCount(['reactions as upvoting' => function(Builder $query) {
                $query->where('name', 'upvote');
            }])
            ->withCount(['reactions as downvoting' => function(Builder $query) {
                $query->where('name', 'downvote');
            }])
            ->latest()
            ->simplePaginate();
    }
}
