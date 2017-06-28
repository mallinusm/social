<?php

namespace Social\Entities\Attributes;

/**
 * Trait UserId
 * @package Social\Entities\Attributes
 */
trait UserId
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @param int $userId
     * @return $this
     */
    public function setUserId(int $userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return bool
     */
    public function hasUserId(): bool
    {
        return $this->userId !== null;
    }
}
