<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;
use Social\Models\Attributes\HasId;
use Social\Models\Relations\{
    BelongsToManyUsers, HasManyMessages
};

/**
 * Class Conversation
 * @package Social\Models
 */
class Conversation extends Model
{
    use BelongsToManyUsers, HasId, HasManyMessages;

    /**
     * @var array
     */
    protected $fillable = [
        'created_at', 'id', 'updated_at'
    ];
}
