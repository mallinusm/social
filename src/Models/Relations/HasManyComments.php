<?php

namespace Social\Models\Relations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Social\Models\Comment;

/**
 * Trait HasManyComments
 * @package Social\Models\Relations
 */
trait HasManyComments
{
    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        /** @var $this Model */
        return $this->hasMany(Comment::class);
    }
}
