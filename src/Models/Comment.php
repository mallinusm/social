<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;
use Social\Models\Attributes\HasId;
use Social\Models\Relations\BelongsToUser;
use Social\Models\Relations\MorphToManyReactions;

/**
 * Class Comment
 * @package Social\Models
 */
class Comment extends Model
{
    use BelongsToUser, HasId, MorphToManyReactions;

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
        'downvoting_count' => 'int',
        'has_downvoting_count' => 'bool',
        'has_upvoting_count' => 'bool',
        'upvoting_count' => 'int',
        'post_id' => 'int',
        'user_id' => 'int'
    ];
}
