<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Reactionable
 * @package Social\Models
 */
final class Reactionable extends Model
{
    use Attributes\Id,
        Attributes\ReactionId,
        Attributes\ReactionableType,
        Attributes\ReactionableId,
        Attributes\CreatedAt,
        Attributes\UpdatedAt;

    /**
     * @var string
     */
    protected $dateFormat = 'U';
}
