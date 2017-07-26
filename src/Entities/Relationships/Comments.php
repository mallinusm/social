<?php

namespace Social\Entities\Relationships;

use Doctrine\ORM\PersistentCollection;
use Social\Entities\Comment;

/**
 * Trait Comments
 * @package Social\Entities\Relationships
 */
trait Comments
{
    /**
     * @var Comment[]
     */
    private $comments;

    /**
     * @param Comment[] $comments
     * @return $this
     */
    public function setComments(array $comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        if ($this->comments instanceof PersistentCollection) {
            return $this->comments->toArray();
        }

        return $this->hasComments() ? $this->comments : [];
    }

    /**
     * @return bool
     */
    public function hasComments(): bool
    {
        return $this->comments !== null;
    }
}
