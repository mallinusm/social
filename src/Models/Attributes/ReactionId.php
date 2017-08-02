<?php

namespace Social\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait ReactionId
 * @package Social\Models\Attributes
 */
trait ReactionId
{
    /**
     * @return int
     */
    public function getReactionId(): int
    {
        /**
         * @var Model $this
         */
        return (int) $this->getAttribute('reaction_id');
    }
}
