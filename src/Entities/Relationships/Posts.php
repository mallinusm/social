<?php

namespace Social\Entities\Relationships;

use Doctrine\ORM\PersistentCollection;
use Social\Entities\Post;

/**
 * Trait Posts
 * @package Social\Entities\Relationships
 */
trait Posts
{
    /**
     * @var Post[]
     */
    private $posts;

    /**
     * @param Post[] $posts
     * @return $this
     */
    public function setPosts(array $posts)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     * @return Post[]
     */
    public function getPosts(): array
    {
        if ($this->posts instanceof PersistentCollection) {
            return $this->posts->toArray();
        }

        return $this->hasPosts() ? $this->posts : [];
    }

    /**
     * @return bool
     */
    public function hasPosts(): bool
    {
        return $this->posts !== null;
    }
}
