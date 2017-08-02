<?php

namespace Social\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait PostId
 * @package Social\Models\Attributes
 */
trait PostId
{
    /**
     * @return int
     */
    public function getPostId(): int
    {
        /**
         * @var Model $this
         */
        return (int) $this->getAttribute('post_id');
    }
}
