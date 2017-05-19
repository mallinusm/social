<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 * @package Social\Models
 */
class Comment extends Model
{
    /**
     * @var array
     */
    public static $createRules = [
        'author_id' => 'required|integer|exists:users,id',
        'content' => 'required|string|max:255',
        'post_id' => 'required|integer|exists:posts,id'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'author_id', 'content', 'post_id'
    ];
}