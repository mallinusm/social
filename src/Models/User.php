<?php

namespace Social\Models;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package Social\Models
 */
class User extends Authenticatable
{
    use HasApiTokens;

    /**
     * @var array
     */
    public static $createRules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @param string $password
     */
    public function setPasswordAttribute(string $password): void
    {
        /**
         * @var Hasher $hasher
         */
        $hasher = app(Hasher::class);

        if ($hasher->needsRehash($password)) {
            $this->attributes['password'] = $hasher->make($password);
        } else {
            $this->attributes['password'] = $password;
        }
    }

    /**
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return (string)($this->getAttribute('email'));
    }
}
