<?php

namespace Social\Models\Relations;

use Closure;
use Illuminate\Database\Eloquent\Builder;
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

    /**
     * @param string $name
     * @return Closure
     */
    private function reactionClosure(string $name): Closure
    {
        return function(Builder $query) use($name): void {
            $query->where('name', $name);
        };
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeWithReactionCounts(Builder $builder): Builder
    {
        return $builder->withCount(['hasReacted as has_upvoting' => $this->reactionClosure('upvote')])
            ->withCount(['hasReacted as has_downvoting' => $this->reactionClosure('downvote')])
            ->withCount(['reactions as upvoting' => $this->reactionClosure('upvote')])
            ->withCount(['reactions as downvoting' => $this->reactionClosure('downvote')]);
    }
}
