<?php

namespace Social\Models\Attributes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait CreatedAt
 * @package Social\Models\Attributes
 */
trait CreatedAt
{
    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        /**
         * @var Model $this
         */
        $createdAt = $this->getAttribute('created_at');

        return $createdAt instanceof Carbon ? $createdAt->getTimestamp() : (int) $createdAt;
    }
}
