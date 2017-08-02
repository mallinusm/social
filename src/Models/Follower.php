<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;
use Social\Models\Attributes\HasId;

/**
 * Class Follower
 * @package Social\Models
 */
class Follower extends Model
{
    use HasId;

    /**
     * @var array
     */
    protected $fillable = [
        'author_id', 'created_at', 'id', 'updated_at', 'user_id'
    ];
}
