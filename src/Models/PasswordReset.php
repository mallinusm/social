<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PasswordReset
 * @package Social\Models
 */
final class PasswordReset extends Model
{
    use Attributes\Token,
        Attributes\CreatedAt;

    /**
     * @var bool
     */
    public $timestamps = false;
}
