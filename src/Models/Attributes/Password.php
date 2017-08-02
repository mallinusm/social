<?php

namespace Social\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait Password
 * @package Social\Models\Attributes
 */
trait Password
{
    /**
     * @return string
     */
    public function getPassword(): string
    {
        /**
         * @var Model $this
         */
        return $this->getAttribute('password');
    }
}
