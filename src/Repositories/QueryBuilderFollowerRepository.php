<?php

namespace Social\Repositories;

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
}
