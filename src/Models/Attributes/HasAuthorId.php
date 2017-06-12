<?php

namespace Social\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasAuthorId
 * @package Social\Models\Attributes
 */
trait HasAuthorId
{
    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        /** @var $this Model */
        return (int) $this->getAttribute('author_id');
    }
}
