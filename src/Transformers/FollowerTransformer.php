<?php

namespace Social\Transformers;

use Illuminate\Support\Collection;
use Social\Entities\Follower;

/**
 * Class FollowerTransformer
 * @package Social\Transformers
 */
final class FollowerTransformer
{
    /**
     * @var UserTransformer
     */
    private $userTransformer;

    /**
     * FollowerTransformer constructor.
     * @param UserTransformer $userTransformer
     */
    public function __construct(UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
    }

    /**
     * @param Follower $follower
     * @return array
     */
    public function transform(Follower $follower): array
    {
        return [
            'author' => $this->userTransformer->transform($follower->getAuthor())
        ];
    }

    /**
     * @param Follower[] $followers
     * @return array
     */
    public function transformMany(array $followers): array
    {
        return (new Collection($followers))->transform(function(Follower $follower): array {
            return $this->transform($follower);
        })->toArray();
    }
}
