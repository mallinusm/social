<?php

namespace Social\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait Content
 * @package Social\Models\Attributes
 */
trait Content
{
    /**
     * @return string
     */
    public function getContent(): string
    {
        /** @var $this Model */
        return $this->getAttribute('content');
    }
}
