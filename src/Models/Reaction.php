<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Reaction
 * @package Social\Models
 */
class Reaction extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'created_at', 'id', 'reactionable_id', 'reactionable_type', 'reaction_type_id', 'updated_at', 'user_id'
    ];
}
