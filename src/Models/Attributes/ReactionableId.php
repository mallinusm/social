<?php

namespace Social\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait ReactionableId
 * @package Social\Models\Attributes
 */
trait ReactionableId
{
    /**
     * @return int
     */
    public function getReactionableId(): int
    {
        /**
         * @var Model $this
         */
        return (int) $this->getAttribute('reactionable_id');
    }
}
