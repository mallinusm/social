<?php

namespace Social\Transformers;

use Social\Entities\Post;

/**
 * Class PostTransformer
 * @package Social\Transformers
 */
final class PostTransformer
{
    /**
     * @param Post $post
     * @return array
     */
    public function transform(Post $post): array
    {
        return [
            'id' => $post->getId(),
            'content' => $post->getContent(),
            'created_at' => $post->getCreatedAt(),
            'updated_at' => $post->getUpdatedAt(),
        ];
    }
}
