<?php

namespace Social\Entities\Attributes;

/**
 * Trait Email
 * @package Social\Entities\Attributes
 */
trait Email
{
    /**
     * @var string
     */
    private $email;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasEmail(): bool
    {
        return $this->email !== null;
    }
}
