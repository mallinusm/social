<?php

namespace Social\Models\Relations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsToMany, MorphToMany
};
use Social\Models\Reaction;

/**
 * Trait MorphToManyReactions
 * @package Social\Models\Relations
 */
trait MorphToManyReactions
{
    /**
     * @return MorphToMany
     */
    public function reactions(): MorphToMany
    {
        /** @var $this Model */
        return $this->morphToMany(Reaction::class, 'reactionable');
    }

    /**
     * @return BelongsToMany
     */
    public function hasReacted(): BelongsToMany
    {
        return $this->reactions()->wherePivot('user_id', auth()->user()->getAuthIdentifier());
    }
}
