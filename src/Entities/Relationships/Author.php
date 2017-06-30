<?php

namespace Social\Entities\Relationships;

use Social\Entities\User;

/**
 * Trait Author
 * @package Social\Entities\Relationships
 */
trait Author
{
    /**
     * @var User
     */
    private $author;

    /**
     * @param User $author
     * @return $this
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }
}
