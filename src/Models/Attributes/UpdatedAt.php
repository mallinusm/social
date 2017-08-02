<?php

namespace Social\Models\Attributes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait UpdatedAt
 * @package Social\Models\Attributes
 */
trait UpdatedAt
{
    /**
     * @return int
     */
    public function getUpdatedAt(): int
    {
        /**
         * @var Model $this
         */
        $updatedAt = $this->getAttribute('updated_at');

        return $updatedAt instanceof Carbon ? $updatedAt->getTimestamp() : (int) $updatedAt;
    }
}
