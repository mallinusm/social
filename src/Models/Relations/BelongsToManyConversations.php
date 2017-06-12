<?php

namespace Social\Models\Relations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Social\Models\Conversation;

/**
 * Trait BelongsToManyConversations
 * @package Social\Models\Relations
 */
trait BelongsToManyConversations
{
    /**
     * @return BelongsToMany
     */
    public function conversations(): BelongsToMany
    {
        /** @var $this Model */
        return $this->belongsToMany(Conversation::class);
    }
}
