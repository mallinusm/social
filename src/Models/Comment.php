<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;
use Social\Models\Attributes\HasId;
use Social\Models\Relations\BelongsToUser;

/**
 * Class Comment
 * @package Social\Models
 */
class Comment extends Model
{
    use BelongsToUser, HasId;

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
}
