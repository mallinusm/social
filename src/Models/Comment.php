<?php

namespace Social\Models;

use Carbon\Carbon;
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
     * @var string
     */
    protected $dateFormat = 'U';

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

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->getAttribute('content');
    }

    /**
     * @return int
     */
    public function getPostId(): int
    {
        return (int) $this->getAttribute('post_id');
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        $createdAt = $this->getAttribute('created_at');

        return $createdAt instanceof Carbon ? $createdAt->getTimestamp() : (int) $createdAt;
    }

    /**
     * @return int
     */
    public function getUpdatedAt(): int
    {
        $updatedAt = $this->getAttribute('updated_at');

        return $updatedAt instanceof Carbon ? $updatedAt->getTimestamp() : (int) $updatedAt;
    }
}
