<?php

namespace Social\Entities\Attributes;

/**
 * Trait Avatar
 * @package Social\Entities\Attributes
 */
trait Avatar
{
    /**
     * @var string
     */
    private $avatar;

    /**
     * @param string $avatar
     * @return $this
     */
    public function setAvatar(string $avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @return bool
     */
    public function hasAvatar(): bool
    {
        return $this->avatar !== null;
    }

    /**
     * @return string
     */
    public function getAvatarLink(): string
    {
        return $this->hasAvatar() ? $this->getAvatar() : '/static/avatar.png';
    }
}
