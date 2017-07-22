<?php

namespace Social\Transformers;

use Illuminate\Support\Collection;
use Social\Entities\Post;

/**
 * Class PostTransformer
 * @package Social\Transformers
 */
final class PostTransformer
{
    /**
     * @var UserTransformer
     */
    private $userTransformer;

    /**
     * @var CommentTransformer
     */
    private $commentTransformer;

    /**
     * @var ReactionableTransformer
     */
    private $reactionableTransformer;

    /**
     * PostTransformer constructor.
     * @param UserTransformer $userTransformer
     * @param CommentTransformer $commentTransformer
     * @param ReactionableTransformer $reactionableTransformer
     */
    public function __construct(UserTransformer $userTransformer,
                                CommentTransformer $commentTransformer,
                                ReactionableTransformer $reactionableTransformer)
    {
        $this->userTransformer = $userTransformer;
        $this->commentTransformer = $commentTransformer;
        $this->reactionableTransformer = $reactionableTransformer;
    }

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
            'author' => $this->userTransformer->transform($post->getAuthor()),
            'user' => $this->userTransformer->transform($post->getUser()),
            'comments' => $post->hasComments() ? $this->commentTransformer->transformMany($post->getComments()) : [],
            'reactionables' => $post->hasReactionables() ?
                $this->reactionableTransformer->transformMany($post->getReactionables()) : []
        ];
    }

    /**
     * @param Post[] $posts
     * @return array
     */
    public function transformMany(array $posts): array
    {
        return (new Collection($posts))->transform(function(Post $post): array {
            return $this->transform($post);
        })->toArray();
    }
}
