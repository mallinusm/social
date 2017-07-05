<?php

namespace Social\Entities\Attributes;

/**
 * Trait ReactionableId
 * @package Social\Entities\Attributes
 */
trait ReactionableId
{
    /**
     * @var int
     */
    private $reactionableId;

    /**
     * @param int $reactionableId
     * @return $this
     */
    public function setReactionableId(int $reactionableId)
    {
        $this->reactionableId = $reactionableId;

        return $this;
    }

    /**
     * @return int
     */
    public function getReactionableId(): int
    {
        return $this->reactionableId;
    }
}
