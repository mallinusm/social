<?php

namespace Social\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Social\Models\Attributes\{
    HasAuthorId, HasId, HasUserId
};

/**
 * Class Post
 * @package Social\Models
 */
class Post extends Model
{
    use HasAuthorId, HasId, HasUserId;

    /**
     * @var string
     */
    protected $dateFormat = 'U';

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
     * @return string
     */
    public function getContent(): string
    {
        return $this->getAttribute('content');
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
