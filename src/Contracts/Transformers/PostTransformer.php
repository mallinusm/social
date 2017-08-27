<?php

namespace Social\Contracts\Transformers;

use Social\Entities\Post;

/**
 * Interface PostTransformer
 * @package Social\Contracts\Transformers
 */
interface PostTransformer
{
    /**
     * @param Post $post
     * @return array
     */
    public function transform(Post $post): array;

    /**
     * @param Post[] $posts
     * @return array
     */
    public function transformMany(array $posts): array;
}
