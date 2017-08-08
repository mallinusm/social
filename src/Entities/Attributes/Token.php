<?php

namespace Social\Entities\Attributes;

/**
 * Trait Token
 * @package Social\Entities\Attributes
 */
trait Token
{
    /**
     * @var string
     */
    private $token;

    /**
     * @param string $token
     * @return $this
     */
    public function setToken(string $token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
