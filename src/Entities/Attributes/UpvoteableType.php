<?php

namespace Social\Entities\Attributes;

/**
 * Trait UpvoteableType
 * @package Social\Entities\Attributes
 */
trait UpvoteableType
{
    /**
     * @var string
     */
    private $upvoteableType;

    /**
     * @param string $upvoteableType
     * @return $this
     */
    public function setUpvoteableType(string $upvoteableType)
    {
        $this->upvoteableType = $upvoteableType;

        return $this;
    }

    /**
     * @return string
     */
    public function getUpvoteableType(): string
    {
        return $this->upvoteableType;
    }
}
