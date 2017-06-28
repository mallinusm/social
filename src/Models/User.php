<?php

namespace Social\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
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
}
