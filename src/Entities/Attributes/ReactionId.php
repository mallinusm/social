<?php

namespace Social\Entities\Attributes;

/**
 * Trait ReactionId
 * @package Social\Entities\Attributes
 */
trait ReactionId
{
    /**
     * @var int
     */
    private $reactionId;

    /**
     * @param int $reactionId
     * @return $this
     */
    public function setReactionId(int $reactionId)
    {
        $this->reactionId = $reactionId;

        return $this;
    }

    /**
     * @return int
     */
    public function getReactionId(): int
    {
        return $this->reactionId;
    }
}
