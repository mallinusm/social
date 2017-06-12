<?php

namespace Social\Models\Relations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Social\Models\Message;

/**
 * Trait HasManyMessages
 * @package Social\Models\Relations
 */
trait HasManyMessages
{
    /**
     * @return HasMany
     */
    public function messages(): HasMany
    {
        /** @var $this Model */
        return $this->hasMany(Message::class);
    }
}
