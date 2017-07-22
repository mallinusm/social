<?php

namespace Social\Entities\Relationships;

use Social\Entities\Post as PostEntity;

/**
 * Trait Post
 * @package Social\Entities\Relationships
 */
trait Post
{
    /**
     * @var PostEntity
     */
    private $post;

    /**
     * @param PostEntity $post
     * @return $this
     */
    public function setPost(PostEntity $post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * @return PostEntity
     */
    public function getPost(): PostEntity
    {
        return $this->post;
    }
}
