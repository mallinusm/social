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
     * @param array $userIds
     * @return Paginator
     */
    public function paginate(array $userIds): Paginator
    {
        /** @var Builder $builder */
        $builder = (new Post)->newQuery()
            ->with(['author', 'comments' => function(HasMany $query): void {
                $query->getQuery()->withReactionCounts()->take(10);
            }, 'comments.user'])
            ->withReactionCounts()
            ->latest();

        if (count($userIds) === 1) {
            $builder->where('user_id', current($userIds));
        } else {
            $builder->whereIn('user_id', $userIds);
        }

        return $builder->simplePaginate();
    }
}
