<?php

namespace Social\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait Id
 * @package Social\Models\Attributes
 */
trait Id
{
    /**
     * @return int
     */
    public function getId(): int
    {
        /** @var $this Model */
        return (int) $this->getAttribute('id');
    }
}
