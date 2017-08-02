<?php

namespace Social\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait UserId
 * @package Social\Models\Attributes
 */
trait UserId
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
