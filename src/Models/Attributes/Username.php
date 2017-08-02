<?php

namespace Social\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait Username
 * @package Social\Models\Attributes
 */
trait Username
{
    /**
     * @return string
     */
    public function getUsername(): string
    {
        /**
         * @var Model $this
         */
        return $this->getAttribute('username');
    }
}
