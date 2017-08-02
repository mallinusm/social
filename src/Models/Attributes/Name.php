<?php

namespace Social\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait Name
 * @package Social\Models\Attributes
 */
trait Name
{
    /**
     * @return string
     */
    public function getName(): string
    {
        /**
         * @var Model $this
         */
        return $this->getAttribute('name');
    }
}
