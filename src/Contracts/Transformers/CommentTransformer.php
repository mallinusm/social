<?php

namespace Social\Contracts\Transformers;

use Social\Entities\Comment;

/**
 * Interface CommentTransformer
 * @package Social\Contracts\Transformers
 */
interface CommentTransformer
{
    /**
     * @param Comment $comment
     * @return array
     */
    public function transform(Comment $comment): array;

    /**
     * @param Comment[] $comments
     * @return array
     */
    public function transformMany(array $comments): array;
}
