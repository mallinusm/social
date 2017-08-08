<?php

namespace Social\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait Token
 * @package Social\Models\Attributes
 */
trait Token
{
    /**
     * @return string
     */
    public function getToken(): string
    {
        /** @var $this Model */
        return $this->getAttribute('token');
    }
}
