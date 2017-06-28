<?php

namespace Social\Entities\Attributes;

/**
 * Trait AuthorId
 * @package Social\Entities\Attributes
 */
trait AuthorId
{
    /**
     * @var int
     */
    private $authorId;

    /**
     * @param int $authorId
     * @return $this
     */
    public function setAuthorId(int $authorId)
    {
        $this->authorId = $authorId;

        return $this;
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * @return bool
     */
    public function hasAuthorId(): bool
    {
        return $this->authorId !== null;
    }
}
