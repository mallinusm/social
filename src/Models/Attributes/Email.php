<?php

namespace Social\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait Email
 * @package Social\Models\Attributes
 */
trait Email
{
    /**
     * @return string
     */
    public function getEmail(): string
    {
        /** @var $this Model */
        return (string) $this->getAttribute('email');
    }
}
