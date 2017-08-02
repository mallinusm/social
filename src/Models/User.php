<?php

namespace Social\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Social\Entities\User as UserEntity;

/**
 * Class User
 * @package Social\Models
 */
class User extends Authenticatable
{
    use HasApiTokens,
        Attributes\Id,
        Attributes\Email,
        Attributes\Name,
        Attributes\Avatar,
        Attributes\Username,
        Attributes\Password,
        Attributes\CreatedAt,
        Attributes\UpdatedAt;

    /**
     * @return UserEntity
     */
    public function toUserEntity(): UserEntity
    {
        return (new UserEntity)->setId($this->getId())
            ->setUsername($this->getUsername())
            ->setAvatar($this->getAvatar())
            ->setName($this->getName())
            ->setEmail($this->getEmail())
            ->setPassword($this->getPassword())
            ->setCreatedAt($this->getCreatedAt())
            ->setUpdatedAt($this->getUpdatedAt());
    }
}
