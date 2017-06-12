<?php

namespace Social\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasEmail
 * @package Social\Models\Attributes
 */
trait HasEmail
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
