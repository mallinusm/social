<?php

namespace Social\Entities\Attributes;

/**
 * Trait UpvoteableId
 * @package Social\Entities\Attributes
 */
trait UpvoteableId
{
    /**
     * @var int
     */
    private $upvoteableId;

    /**
     * @param int $upvoteableId
     * @return $this
     */
    public function setUpvoteableId(int $upvoteableId)
    {
        $this->upvoteableId = $upvoteableId;

        return $this;
    }

    /**
     * @return int
     */
    public function getUpvoteableId(): int
    {
        return $this->upvoteableId;
    }
}
