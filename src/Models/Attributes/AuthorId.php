<?php

namespace Social\Models\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait AuthorId
 * @package Social\Models\Attributes
 */
trait AuthorId
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
