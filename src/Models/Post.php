<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Post
 * @package Social\Models
 */
final class Post extends Model
{
    use Attributes\Id,
        Attributes\Content,
        Attributes\AuthorId,
        Attributes\UserId,
        Attributes\CreatedAt,
        Attributes\UpdatedAt;

    /**
     * @var string
     */
    protected $dateFormat = 'U';
}
