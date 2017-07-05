<?php

namespace Social\Entities\Attributes;

/**
 * Trait ReactionableType
 * @package Social\Entities\Attributes
 */
trait ReactionableType
{
    /**
     * @var string
     */
    private $reactionableType;

    /**
     * @param string $reactionableType
     * @return $this
     */
    public function setReactionableType(string $reactionableType)
    {
        $this->reactionableType = $reactionableType;

        return $this;
    }

    /**
     * @return string
     */
    public function getReactionableType(): string
    {
        return $this->reactionableType;
    }
}
