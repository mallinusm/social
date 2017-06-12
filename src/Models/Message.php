<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;
use Social\Models\Attributes\HasConversationId;
use Social\Models\Relations\BelongsToUser;

/**
 * Class Message
 * @package Social\Models
 */
class Message extends Model
{
    use BelongsToUser, HasConversationId;

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
}
