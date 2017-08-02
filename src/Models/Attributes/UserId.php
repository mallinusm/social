<?php

namespace Social\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasUserId
 * @package Social\Models\Attributes
 */
trait HasUserId
{
    /**
     * @return int
     */
    public function getUserId(): int
    {
        /** @var $this Model */
        return (int) $this->getAttribute('user_id');
    }
}
