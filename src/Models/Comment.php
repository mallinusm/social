<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 * @package Social\Models
 */
class Comment extends Model
{
    use Attributes\Id,
        Attributes\PostId,
        Attributes\Content,
        Attributes\CreatedAt,
        Attributes\UpdatedAt;

    /**
     * @var string
     */
    protected $dateFormat = 'U';
}
