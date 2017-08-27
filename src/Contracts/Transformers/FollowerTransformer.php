<?php

namespace Social\Contracts\Transformers;

use Social\Entities\Follower;

/**
 * Interface FollowerTransformer
 * @package Social\Contracts\Transformers
 */
interface FollowerTransformer
{
    /**
     * @param Follower $follower
     * @return array
     */
    public function transform(Follower $follower): array;

    /**
     * @param Follower[] $followers
     * @return array
     */
    public function transformMany(array $followers): array;
}
