<?php

namespace Social\Transformers\Followers;

use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use Social\Entities\Follower;
use Social\Transformers\Users\UserTransformer;

/**
 * Class FollowerTransformer
 * @package Social\Transformers\Followers
 */
final class FollowerTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = [
        'author'
    ];

    /**
     * @return array
     */
    public function transform(): array
    {
        return [];
    }

    /**
     * @param Follower $follower
     * @return Item
     */
    public function includeAuthor(Follower $follower): Item
    {
        $author = $follower->getAuthor();

        return $this->item($author, new UserTransformer);
    }
}
