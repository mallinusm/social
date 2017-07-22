<?php

namespace Social\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Reactionable
 * @package Social\Models
 */
class Reactionable extends Model
{
    /**
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * @var array
     */
    protected $fillable = [
        'created_at', 'id', 'reactionable_id', 'reactionable_type', 'reaction_id', 'updated_at', 'user_id'
    ];

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

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->getAttribute('id');
    }

    /**
     * @return int
     */
    public function getReactionId(): int
    {
        return (int) $this->getAttribute('reaction_id');
    }

    /**
     * @return int
     */
    public function getReactionableId(): int
    {
        return (int) $this->getAttribute('reactionable_id');
    }

    /**
     * @return string
     */
    public function getReactionableType(): string
    {
        return $this->getAttribute('reactionable_type');
    }
}
