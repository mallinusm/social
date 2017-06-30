<?php

namespace Social\Entities\Relationships;

use Social\Entities\User as UserEntity;

/**
 * Trait User
 * @package Social\Entities\Relationships
 */
trait User
{
    /**
     * @var UserEntity
     */
    private $user;

    /**
     * @return UserEntity
     */
    public function getUser(): UserEntity
    {
        return $this->user;
    }

    /**
     * @param UserEntity $user
     * @return $this
     */
    public function setUser(UserEntity $user)
    {
        $this->user = $user;

        return $this;
    }
}
