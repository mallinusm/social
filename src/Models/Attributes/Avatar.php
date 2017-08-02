<?php

namespace Social\Models\Attributes;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\UrlGenerator;

/**
 * Trait Avatar
 * @package Social\Models\Attributes
 */
trait Avatar
{
    /**
     * @return string
     */
    public function getAvatar(): string
    {
        /**
         * @var Model $this
         */
        return $this->getAttribute('avatar');
    }

    /**
     * @param null|string $avatar
     * @return string
     */
    public function getAvatarAttribute(?string $avatar): string
    {
        if (is_null($avatar)) {
            return '/static/avatar.png';
        }

        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = Container::getInstance()->make(UrlGenerator::class);

        return $urlGenerator->route('avatars.show', compact('avatar'));
    }
}
