<?php

namespace Social\Entities;

/**
 * Class Post
 * @package Social\Entities
 */
final class Post
{
    use Attributes\Id,
        Attributes\AuthorId,
        Attributes\Content,
        Attributes\UserId,
        Attributes\CreatedAt,
        Attributes\UpdatedAt;
}
