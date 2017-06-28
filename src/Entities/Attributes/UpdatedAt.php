<?php

namespace Social\Entities\Attributes;

/**
 * Trait UpdatedAt
 * @package Social\Entities\Attributes
 */
trait UpdatedAt
{
    /**
     * @var int
     */
    private $updatedAt;

    /**
     * @return int
     */
    public function getUpdatedAt(): int
    {
        return $this->updatedAt;
    }

    /**
     * @param int $updatedAt
     * @return $this
     */
    public function setUpdatedAt(int $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasUpdatedAt(): bool
    {
        return $this->updatedAt !== null;
    }
}
