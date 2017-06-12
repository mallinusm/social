<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Social\Models\Attributes\{
    HasAuthorId, HasId, HasUserId
};
use Social\Models\Relations\{
    BelongsToAuthor, HasManyComments, MorphToManyReactions
};

/**
 * Class Post
 * @package Social\Models
 */
class Post extends Model
{
    use BelongsToAuthor, HasAuthorId, HasManyComments, HasId, HasUserId, MorphToManyReactions;

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
        'author_id' => 'int',
        'downvoting_count' => 'int',
        'has_downvoting_count' => 'bool',
        'has_upvoting_count' => 'bool',
        'upvoting_count' => 'int',
        'user_id' => 'int'
    ];

    /**
     * @return BelongsToMany
     */
    public function hasReacted(): BelongsToMany
    {
        return $this->reactions()->wherePivot('user_id', auth()->user()->getAuthIdentifier());
    }
}
