<?php

namespace Social\Entities\Relationships;

use Social\Entities\Comment as CommentEntity;

/**
 * Trait Comment
 * @package Social\Entities\Relationships
 */
trait Comment
{
    /**
     * @var CommentEntity
     */
    private $comment;

    /**
     * @param CommentEntity $comment
     * @return $this
     */
    public function setComment(CommentEntity $comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return CommentEntity
     */
    public function getComment(): CommentEntity
    {
        return $this->comment;
    }
}
