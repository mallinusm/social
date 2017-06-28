<?php

namespace Social\Entities\Attributes;

/**
 * Trait CreatedAt
 * @package Social\Entities\Attributes
 */
trait CreatedAt
{
    /**
     * @var int
     */
    private $createdAt;

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * @param int $createdAt
     * @return $this
     */
    public function setCreatedAt(int $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasCreatedAt(): bool
    {
        return $this->createdAt !== null;
    }
}
