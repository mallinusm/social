<?php

namespace Social\Repositories;

use Illuminate\Support\Collection;
use Social\Contracts\FollowerRepository;
use Social\Models\Follower;

/**
 * Class QueryBuilderFollowerRepository
 * @package Social\Repositories
 */
class QueryBuilderFollowerRepository extends QueryBuilderRepository implements FollowerRepository
{
    /**
     * @return string
     */
    protected function getTable(): string
    {
        return 'followers';
    }

    /**
     * @param int $authorId
     * @param int $userId
     * @return Follower
     */
    public function follow(int $authorId, int $userId): Follower
    {
        return (new Follower)->fill($this->insert([
            'author_id' => $authorId,
            'user_id' => $userId
        ]));
    }

    /**
     * @param int $id
     * @return bool
     */
    public function unfollow(int $id): bool
    {
        return (bool) $this->getBuilder()->delete($id);
    }

    /**
     * @param int $authorId
     * @param int $userId
     * @return bool
     */
    public function isFollowing(int $authorId, int $userId): bool
    {
        return $this->getBuilder()
            ->where('author_id', $authorId)
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * @param int $authorId
     * @return Collection
     */
    public function getFollowingsIds(int $authorId): Collection
    {
        return $this->getBuilder()->where('author_id', $authorId)->pluck('user_id');
    }
}
