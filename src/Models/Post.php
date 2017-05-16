<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Post
 * @package Social\Models
 */
class Post extends Model
{
    /**
     * @var array
     */
    public static $createRules = [
        'author_id' => 'required|integer|exists:users,id',
        'content' => 'required|string|max:255',
        'user_id' => 'required|integer|exists:users,id'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'author_id', 'content', 'user_id'
    ];
}