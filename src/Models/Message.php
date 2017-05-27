<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Message
 * @package Social\Models
 */
class Message extends Model
{
    /**
     * @var array
     */
    public static $createRules = [
        'content' => 'required|string|max:255',
        'conversation_id' => 'required|integer|exists:conversations,id',
        'user_id' => 'required|integer|exists:users,id'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'content', 'conversation_id', 'user_id'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'conversation_id' => 'int', 'user_id' => 'int'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}