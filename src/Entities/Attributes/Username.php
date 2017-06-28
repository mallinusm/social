<?php

namespace Social\Entities\Attributes;

/**
 * Trait Username
 * @package Social\Entities\Attributes
 */
trait Username
{
    /**
     * @var string
     */
    private $username;

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return bool
     */
    public function hasUsername(): bool
    {
        return $this->username !== null;
    }
}
