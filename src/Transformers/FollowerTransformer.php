<?php

namespace Social\Transformers;

use Illuminate\Support\Collection;
use Social\Contracts\Transformers\{
    FollowerTransformer as FollowerTransformerContract,
    UserTransformer as UserTransformerContract
};
use Social\Entities\Follower;

/**
 * Class FollowerTransformer
 * @package Social\Transformers
 */
final class FollowerTransformer implements FollowerTransformerContract
{
    /**
     * @var UserTransformerContract
     */
    private $userTransformer;

    /**
     * FollowerTransformer constructor.
     * @param UserTransformerContract $userTransformer
     */
    public function __construct(UserTransformerContract $userTransformer)
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
