<?php

namespace Social\Entities;

use Social\Helpers\InteractsWithDoctrine;

/**
 * Class Post
 * @package Social\Entities
 */
final class Post
{
    use InteractsWithDoctrine,
        Attributes\Id,
        Attributes\AuthorId,
        Attributes\Content,
        Attributes\UserId,
        Attributes\CreatedAt,
        Attributes\UpdatedAt;
}
