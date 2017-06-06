<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Follower
 * @package Social\Models
 */
class Follower extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'author_id', 'created_at', 'id', 'updated_at', 'user_id'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->getAttribute('id'));
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return (int)($this->getAttribute('author_id'));
    }
}
