<?php

namespace Social\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait ReactionableType
 * @package Social\Models\Attributes
 */
trait ReactionableType
{
    /**
     * @return string
     */
    public function getReactionableType(): string
    {
        /**
         * @var Model $this
         */
        return $this->getAttribute('reactionable_type');
    }
}
