<?php

namespace Social\Models\Relations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Social\Models\User;

/**
 * Trait BelongsToUser
 * @package Social\Models\Relations
 */
trait BelongsToUser
{
    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        /** @var $this Model */
        return $this->belongsTo(User::class);
    }
}
