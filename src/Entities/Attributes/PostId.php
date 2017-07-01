<?php

namespace Social\Entities\Attributes;

/**
 * Trait PostId
 * @package Social\Entities\Attributes
 */
trait PostId
{
    /**
     * @var int
     */
    private $postId;

    /**
     * @param int $postId
     * @return $this
     */
    public function setPostId(int $postId)
    {
        $this->postId = $postId;

        return $this;
    }

    /**
     * @return int
     */
    public function getPostId(): int
    {
        return $this->postId;
    }
}
