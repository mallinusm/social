<?php

namespace Social\Models\Relations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Social\Models\Post;

/**
 * Trait HasManyPosts
 * @package Social\Models\Relations
 */
trait HasManyPosts
{
    /**
     * @return HasMany
     */
    public function posts(): HasMany
    {
        /** @var $this Model */
        return $this->hasMany(Post::class);
    }
}
