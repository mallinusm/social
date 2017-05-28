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
        'author_id' => 'required|integer|exists:users,id',
        'content' => 'required|string|max:255',
        'post_id' => 'required|integer|exists:posts,id'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'author_id', 'content', 'created_at', 'id', 'post_id', 'updated_at'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'post_id' => 'int'
    ];

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}