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
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->getAttribute('id');
    }
}
