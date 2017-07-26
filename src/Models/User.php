<?php

namespace Social\Models;

use Illuminate\Container\Container;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Social\Entities\User as UserEntity;
use Social\Models\Attributes\{
    HasEmail, HasId
};
use Social\Models\Relations\{
    BelongsToManyConversations, HasManyPosts
};

/**
 * Class User
 * @package Social\Models
 */
class User extends Authenticatable
{
    use BelongsToManyConversations, HasApiTokens, HasEmail, HasId, HasManyPosts;

    /**
     * @var array
     */
    protected $fillable = [
        'created_at', 'email', 'id', 'name', 'password', 'updated_at'
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->getAttribute('avatar');
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->getAttribute('username');
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

    /**
     * @return UserEntity
     */
    public function toUserEntity(): UserEntity
    {
        return (new UserEntity)->setId($this->getId())
            ->setUsername($this->getUsername())
            ->setAvatar($this->getAttribute('avatar'))
            ->setName($this->getAttribute('name'))
            ->setEmail($this->getAttribute('email'))
            ->setPassword($this->getAttribute('password'))
            ->setCreatedAt($this->getAttribute('created_at')->getTimestamp())
            ->setUpdatedAt($this->getAttribute('updated_at')->getTimestamp());
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getAttribute('name');
    }
}
