<?php

namespace Social\Entities\Relationships;

use Doctrine\ORM\PersistentCollection;
use Social\Entities\Follower;

/**
 * Trait Followers
 * @package Social\Entities\Relationships
 */
trait Followers
{
    /**
     * @var Follower[]
     */
    private $followers;

    /**
     * @param Follower[] $followers
     * @return $this
     */
    public function setFollowers(array $followers)
    {
        $this->followers = $followers;

        return $this;
    }

    /**
     * @return Follower[]
     */
    public function getFollowers(): array
    {
        if ($this->followers instanceof PersistentCollection) {
            return $this->followers->toArray();
        }

        return $this->followers;
    }

    /**
     * @return bool
     */
    public function hasFollowers(): bool
    {
        return $this->followers !== null && count($this->followers) > 0;
    }
}
