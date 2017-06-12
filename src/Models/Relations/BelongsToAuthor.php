<?php

namespace Social\Models\Relations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Social\Models\User;

/**
 * Trait HasAuthor
 * @package Social\Models\Relations
 */
trait BelongsToAuthor
{
    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        /** @var $this Model */
        return $this->belongsTo(User::class);
    }
}
