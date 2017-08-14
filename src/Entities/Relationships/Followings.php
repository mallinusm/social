<?php

namespace Social\Entities\Relationships;

use Doctrine\ORM\PersistentCollection;
use Social\Entities\Follower;

/**
 * Trait Followings
 * @package Social\Entities\Relationships
 */
trait Followings
{
    /**
     * @var Follower[]
     */
    private $followings;

    /**
     * @param Follower[] $followings
     * @return $this
     */
    public function setFollowings(array $followings)
    {
        $this->followings = $followings;

        return $this;
    }

    /**
     * @return Follower[]
     */
    public function getFollowings(): array
    {
        if ($this->followings instanceof PersistentCollection) {
            return $this->followings->toArray();
        }

        return $this->followings;
    }

    /**
     * @return bool
     */
    public function hasFollowings(): bool
    {
        return $this->followings !== null && count($this->followings) > 0;
    }
}
