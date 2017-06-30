<?php

namespace Social\Transformers;

use Social\Entities\Comment;

/**
 * Class CommentTransformer
 * @package Social\Transformers
 */
final class CommentTransformer
{
    /**
     * @param Comment $comment
     * @return array
     */
    public function transform(Comment $comment): array
    {
        return [];
    }
}
