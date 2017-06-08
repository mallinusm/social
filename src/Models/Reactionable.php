<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Reactionable
 * @package Social\Models
 */
class Reactionable extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'created_at', 'id', 'reactionable_id', 'reactionable_type', 'reaction_id', 'updated_at', 'user_id'
    ];
}
