<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'content' => 'required|string|max:255',
        'post_id' => 'required|integer|exists:posts,id',
        'user_id' => 'required|integer|exists:users,id',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'content', 'created_at', 'id', 'post_id', 'updated_at', 'user_id'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'post_id' => 'int', 'user_id' => 'int'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}