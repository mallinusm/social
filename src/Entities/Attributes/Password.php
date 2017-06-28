<?php

namespace Social\Entities\Attributes;

/**
 * Trait Password
 * @package Social\Entities\Attributes
 */
trait Password
{
    /**
     * @var string
     */
    private $password;

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasPassword(): bool
    {
        return $this->password !== null;
    }
}
