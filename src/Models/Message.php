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
    protected $fillable = [
        'content', 'conversation_id', 'created_at', 'id', 'updated_at', 'user_id'
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

    /**
     * @return int
     */
    public function getConversationId(): int
    {
        return (int)($this->getAttribute('conversation_id'));
    }
}
