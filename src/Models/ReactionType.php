<?php

namespace Social\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ReactionType
 * @package Social\Models
 */
class ReactionType extends Model
{
    /**
     * @var string
     */
    protected $table = 'reaction_types';

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int) $this->getAttribute('id');
    }
}
