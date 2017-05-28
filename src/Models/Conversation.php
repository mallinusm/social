<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Conversation
 * @package Social\Models
 */
class Conversation extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'created_at', 'id', 'updated_at'
    ];

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->getAttribute('id'));
    }
}