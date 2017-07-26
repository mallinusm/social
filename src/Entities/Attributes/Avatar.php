<?php

namespace Social\Entities\Attributes;

use Illuminate\Container\Container;
use Illuminate\Contracts\Routing\UrlGenerator;

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
     * @return UrlGenerator
     */
    private function getUrlGenerator(): UrlGenerator
    {
        return Container::getInstance()->make(UrlGenerator::class);
    }

    /**
     * @return string
     */
    public function getAvatarLink(): string
    {
        if (! $this->hasAvatar()) {
            return '/static/avatar.png';
        }

        $avatar = $this->getAvatar();

        if (filter_var($avatar, FILTER_VALIDATE_URL)) {
            return $avatar;
        }

        return $this->getUrlGenerator()->route('avatars.show', compact('avatar'));
    }
}
