<?php

namespace Social\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasId
 * @package Social\Models\Attributes
 */
trait HasId
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
