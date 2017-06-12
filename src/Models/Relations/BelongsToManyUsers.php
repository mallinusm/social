<?php

namespace Social\Models\Relations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Social\Models\User;

/**
 * Trait BelongsToManyUsers
 * @package Social\Models\Relations
 */
trait BelongsToManyUsers
{
    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        /** @var $this Model */
        return $this->belongsToMany(User::class);
    }
}
