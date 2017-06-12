<?php

namespace Social\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasConversationId
 * @package Social\Models\Attributes
 */
trait HasConversationId
{
    /**
     * @return int
     */
    public function getConversationId(): int
    {
        /** @var $this Model */
        return (int) $this->getAttribute('conversation_id');
    }
}
