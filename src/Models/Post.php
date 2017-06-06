<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    HasMany
};

/**
 * Class Post
 * @package Social\Models
 */
class Post extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'author_id', 'content', 'created_at', 'id', 'updated_at', 'user_id'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'author_id' => 'int', 'user_id' => 'int'
    ];

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return (int)($this->getAttribute('user_id'));
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return (int)($this->getAttribute('author_id'));
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->getAttribute('id'));
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
