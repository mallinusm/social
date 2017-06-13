<?php

namespace Social\Repositories;

use Closure;
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
     * @return Closure
     */
    private function upvoteClosure(): Closure
    {
        return function(Builder $query) {
            $query->where('name', 'upvote');
        };
    }

    /**
     * @return Closure
     */
    private function downvoteClosure(): Closure
    {
        return function(Builder $query) {
            $query->where('name', 'downvote');
        };
    }

    /**
     * @param int $userId
     * @return Paginator
     */
    public function paginate(int $userId): Paginator
    {
        return (new Post)->newQuery()
            ->with('author')
            ->with('comments.user')
            ->with(['comments' => function(HasMany $query) {
                $query->getQuery()
                    ->withCount(['hasReacted as has_upvoting' => $this->upvoteClosure()])
                    ->withCount(['hasReacted as has_downvoting' => $this->downvoteClosure()])
                    ->withCount(['reactions as upvoting' => $this->upvoteClosure()])
                    ->withCount(['reactions as downvoting' => $this->downvoteClosure()])
                    ->latest()
                    ->take(10);
            }])
            ->withCount(['hasReacted as has_upvoting' => $this->upvoteClosure()])
            ->withCount(['hasReacted as has_downvoting' => $this->downvoteClosure()])
            ->withCount(['reactions as upvoting' => $this->upvoteClosure()])
            ->withCount(['reactions as downvoting' => $this->downvoteClosure()])
            ->where('user_id', $userId)
            ->latest()
            ->simplePaginate();
    }
}
