<?php

namespace Social\Entities;

use Social\Helpers\InteractsWithDoctrine;

/**
 * Class User
 * @package Social\Entities
 */
final class User
{
    use InteractsWithDoctrine,
        Attributes\Id,
        Attributes\Email,
        Attributes\Name,
        Attributes\Password,
        Attributes\Avatar,
        Attributes\Username,
        Attributes\CreatedAt,
        Attributes\UpdatedAt;
}
