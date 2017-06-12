<?php

namespace Social\Models\Relations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
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
}
